<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.login.index');
    }

    public function logUser(Request $request)
    {
        try {
            $attributes = request()->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($attributes, $request->input('remember'))) {
                $user = User::where('email', '=', $request->input('email'))->first();
                // if (!$user->hasRole(['admin', 'boss', 'cashier', 'accountant'])) {
                //     return response()->json(['ok' => false, 'message' => "Vous n'avez pas le droit de vous connecter"]);
                // }
                session()->regenerate();

                return response()->json(['ok' => true, 'message' => 'Vous êtes connecté.']);
            } else {
                // return back()->withErrors(['email' => '']);

                return response()->json(['ok' => false, 'message' => 'Email ou password invalide.']);
            }
        } catch (\Exception $e) {
            // return response()->json(['ok' => false, 'message' => $e->getMessage()]);

            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();

            return back()->with(['success' => 'Vous êtes déconnecté.']);
        } catch (\Exception $e) {
            // Gérez l'erreur ici, vous pouvez la logger ou retourner une réponse adaptée à l'erreur.
            return back()->with(['error' => 'Une erreur s\'est produite. Veuillez réessayer.']);

            // return back()->with(['error' => $e->getMessage()]);
        }
    }

    public function forgotPasswordFormGet()
    {
        return view('pages.auth.password-forgot.index');
    }

    public function sendEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            // Envoyer le lien de réinitialisation du mot de passe
            $status = Password::sendResetLink(
                $request->only('email')
            );
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __(' Nous vous avons envoyé par email le lien de réinitialisation du mot de passe ! Le lien expirera dans 15 minutes.'), 'ok' => true]);
            } else {
                return response()->json(['error' => __('Une erreur est survenue'), 'ok' => true], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);

            // return response()->json(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    public function resetPass($token)
    {
        return view('pages.auth.reset-password.index', ['token' => $token]);
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->setRememberToken(Str::random(60));

                    $user->save();
                    session()->regenerate();

                    event(new PasswordReset($user));
                    Auth::logoutOtherDevices($password);
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json(['message' => __(' Votre mot de passe a été mis à jour avec succes .'), 'ok' => true]);
            } else {
                // return response()->json(['error' => __('Password reset failed.'), 'ok' => false], 422);
                return response()->json(['message' => 'Une erreur est survenu. Verifiez les information entrées', 'ok' => false]);
            }
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }

    public function passwordChanged()
    {
        return view('authentification.sign-in.success');
    }
}
