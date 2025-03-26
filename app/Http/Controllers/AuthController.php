<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
            $attributes = request()->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($attributes, $request->input('remember'))) {
                $user = Auth::user();
                session()->regenerate();

                // Journaliser la connexion réussie
                $this->activityLogger->log(
                    'login',
                    "Connexion réussie de l'utilisateur {$user->username}",
                    $user
                );

                // Si la requête attend une réponse JSON (AJAX)
                if ($request->expectsJson()) {
                    $redirectUrl = $this->getRedirectUrlForUser($user);

                    return response()->json([
                        'ok' => true,
                        'message' => 'Vous êtes connecté.',
                        'redirect' => $redirectUrl,
                    ]);
                }

                // Sinon, redirection directe pour les requêtes non-AJAX
                return redirect($this->getRedirectUrlForUser($user));
            } else {
                // Journaliser la tentative de connexion échouée
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
            // Journaliser l'erreur de validation
            $this->activityLogger->log(
                'error',
                "Erreur de validation lors de la tentative de connexion",
                null,
                ['errors' => $e->errors(), 'ip' => $request->ip()]
            );

            throw $e;
        } catch (\Exception $e) {
            // Journaliser les autres exceptions
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
        if ($user->can('access-all')) {
            return RouteServiceProvider::GATEWAY;
        }

        if ($user->can('access-opti-hr')) {
            return RouteServiceProvider::OPTI_HR_HOME;
        }

        if ($user->can('access-recours')) {
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

        // Journaliser la déconnexion
        $this->activityLogger->log(
            'logout',
            "Déconnexion de l'utilisateur {$username}"
        );

        return back()->with(['success' => 'Vous êtes déconnecté.']);
    }

    /**
     * Affiche le formulaire de mot de passe oublié
     */
    public function forgotPasswordFormGet()
    {
        return view('auth.password-forgot.index');
    }

    /**
     * Envoie l'email de réinitialisation du mot de passe
     */
    public function sendEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            // Envoyer le lien de réinitialisation du mot de passe
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                // Journaliser l'envoi du lien de réinitialisation
                $this->activityLogger->log(
                    'created',
                    "Envoi du lien de réinitialisation de mot de passe à {$request->email}",
                    null,
                    ['email' => $request->email]
                );

                return response()->json([
                    'message' => __('Nous vous avons envoyé par email le lien de réinitialisation du mot de passe ! Le lien expirera dans 15 minutes.'),
                    'ok' => true
                ]);
            } else {
                // Journaliser l'échec
                $this->activityLogger->log(
                    'error',
                    "Échec de l'envoi du lien de réinitialisation à {$request->email}",
                    null,
                    ['email' => $request->email, 'status' => $status]
                );

                return response()->json([
                    'error' => __('Une erreur est survenue. Vérifiez que votre adresse email est correcte.'),
                    'ok' => false
                ], 422);
            }
        } catch (ValidationException $e) {
            // Journaliser l'erreur de validation
            $this->activityLogger->log(
                'error',
                "Erreur de validation lors de la demande de réinitialisation",
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
     * @param Request $request
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
                'ok' => true
            ]);
        }

        // Journaliser l'échec
        $this->activityLogger->log(
            'error',
            "Échec de la réinitialisation du mot de passe",
            null,
            ['email' => $request->email, 'status' => $status]
        );

        return response()->json([
            'message' => __($status),
            'ok' => false
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