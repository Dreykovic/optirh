<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Affiche la page de connexion
     */
    public function login()
    {
        // Si l'utilisateur est déjà connecté, rediriger vers la page appropriée
        if (Auth::check()) {
            return redirect($this->getRedirectUrlForUser(Auth::user()));
        }

        return view('auth.login.index');
    }

    /**
     * Authentifie l'utilisateur
     */
    public function logUser(Request $request)
    {
        try {
            // Rate limiting : 5 tentatives par minute par IP
            $rateLimitKey = 'login-attempt:'.$request->ip();

            if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);

                $this->activityLogger->log(
                    'denied',
                    "Trop de tentatives de connexion depuis l'IP: {$request->ip()}",
                    null,
                    ['ip' => $request->ip(), 'wait_seconds' => $seconds]
                );

                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
                    ], 429);
                }

                return back()->withErrors([
                    'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
                ]);
            }

            $attributes = request()->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Vérifier si l'utilisateur existe et est actif AVANT Auth::attempt
            $user = User::where('email', $attributes['email'])->first();

            if ($user && $user->status !== 'ACTIVATED') {
                RateLimiter::hit($rateLimitKey, 60);

                $this->activityLogger->log(
                    'denied',
                    "Tentative de connexion d'un compte {$user->status}: {$attributes['email']}",
                    $user,
                    ['ip' => $request->ip()]
                );

                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Votre compte est désactivé. Contactez l\'administrateur.',
                    ], 403);
                }

                return back()->withErrors([
                    'email' => 'Votre compte est désactivé. Contactez l\'administrateur.',
                ]);
            }

            if (Auth::attempt($attributes, $request->input('remember'))) {
                // Connexion réussie : réinitialiser le rate limiter
                RateLimiter::clear($rateLimitKey);

                $user = Auth::user();
                session()->regenerate();

                $this->activityLogger->log(
                    'login',
                    "Connexion réussie de l'utilisateur {$user->username}",
                    $user
                );

                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => true,
                        'message' => 'Vous êtes connecté.',
                        'redirect' => $this->getRedirectUrlForUser($user),
                    ]);
                }

                return redirect($this->getRedirectUrlForUser($user));
            } else {
                // Connexion échouée : incrémenter le rate limiter
                RateLimiter::hit($rateLimitKey, 60);

                $this->activityLogger->log(
                    'denied',
                    "Tentative de connexion échouée pour l'email: {$request->input('email')}",
                    null,
                    ['ip' => $request->ip()]
                );

                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Email ou mot de passe invalide.',
                    ]);
                }

                return back()->withErrors([
                    'email' => 'Email ou mot de passe invalide.',
                ]);
            }
        } catch (ValidationException $e) {
            $this->activityLogger->log(
                'error',
                'Erreur de validation lors de la tentative de connexion',
                null,
                ['errors' => $e->errors(), 'ip' => $request->ip()]
            );

            throw $e;
        } catch (\Exception $e) {
            $this->activityLogger->log(
                'error',
                "Exception lors de la tentative de connexion: {$e->getMessage()}",
                null,
                ['ip' => $request->ip()]
            );

            throw $e;
        }
    }

    /**
     * Détermine l'URL de redirection en fonction des permissions de l'utilisateur
     */
    private function getRedirectUrlForUser($user)
    {
        // Priorité des redirections en fonction des permissions
        if ($user->can('access-un-all')) {
            return RouteServiceProvider::GATEWAY;
        }

        if ($user->can('access-un-opti-hr')) {
            return RouteServiceProvider::OPTI_HR_HOME;
        }

        if ($user->can('access-un-recours')) {
            return RouteServiceProvider::RECOURS_HOME;
        }

        // Redirection par défaut
        return RouteServiceProvider::OPTI_HR_HOME;
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $username = $user ? $user->username : 'Utilisateur inconnu';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $this->activityLogger->log(
            'logout',
            "Déconnexion de l'utilisateur {$username}"
        );

        // Rediriger vers login au lieu de back() pour éviter les erreurs sur pages protégées
        return redirect()->route('login')->with('success', 'Vous êtes déconnecté.');
    }

    /**
     * Affiche le formulaire de mot de passe oublié
     */
    public function forgotPasswordFormGet()
    {
        return view('auth.password-forgot.index');
    }

    /**
     * Envoie l'email de réinitialisation du mot de passe (asynchrone)
     */
    public function sendEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $email = $request->input('email');

            // Vérifier si l'utilisateur existe (sans révéler l'information)
            $user = User::where('email', $email)->first();

            // Message générique pour ne pas révéler si l'email existe
            $successMessage = 'Si cette adresse est associée à un compte, vous recevrez un email sous peu. Le lien expire dans 60 minutes.';

            if (! $user) {
                // Log mais retourner succès pour ne pas révéler si l'email existe
                $this->activityLogger->log(
                    'info',
                    "Demande de réinitialisation pour email inexistant: {$email}",
                    null,
                    ['email' => $email, 'ip' => $request->ip()]
                );

                return response()->json([
                    'message' => $successMessage,
                    'ok' => true,
                ]);
            }

            // Envoyer de manière asynchrone (après la réponse HTTP)
            dispatch(function () use ($email) {
                Password::sendResetLink(['email' => $email]);
            })->afterResponse();

            $this->activityLogger->log(
                'created',
                "Demande de réinitialisation de mot de passe pour {$email}",
                $user,
                ['email' => $email]
            );

            return response()->json([
                'message' => $successMessage,
                'ok' => true,
            ]);

        } catch (ValidationException $e) {
            $this->activityLogger->log(
                'error',
                'Erreur de validation lors de la demande de réinitialisation',
                null,
                ['errors' => $e->errors(), 'ip' => $request->ip()]
            );

            throw $e;
        }
    }

    /**
     * Affiche le formulaire de réinitialisation de mot de passe
     */
    public function resetPass($token)
    {
        return view('auth.reset-password.index', ['token' => $token]);
    }

    /**
     * Change le mot de passe de l'utilisateur
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {

        $validated = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
                Auth::logoutOtherDevices($password);

                // Journaliser la réinitialisation du mot de passe
                $this->activityLogger->log(
                    'updated',
                    "Réinitialisation du mot de passe pour l'utilisateur {$user->username}",
                    $user
                );
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __('Votre mot de passe a été mis à jour avec succès.'),
                'ok' => true,
            ]);
        }

        // Journaliser l'échec
        $this->activityLogger->log(
            'error',
            'Échec de la réinitialisation du mot de passe',
            null,
            ['email' => $request->email, 'status' => $status]
        );

        return response()->json([
            'message' => __($status),
            'ok' => false,
        ]);

    }

    /**
     * Affiche la page de confirmation après le changement de mot de passe
     */
    public function passwordChanged()
    {
        return view('authentification.sign-in.success');
    }
}
