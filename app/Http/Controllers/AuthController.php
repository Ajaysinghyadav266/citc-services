<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Approver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    //  GOOGLE REDIRECT — student login path
    //  Always clears the approver flag so a student never accidentally
    //  lands on the approver dashboard after visiting /approver-login first.
    public function redirectToGoogle()
    {
        session()->forget('approver_login_intent');
        return Socialite::driver('google')->redirect();
    }

    //  GOOGLE REDIRECT — approver login path (called from /approver-login/google)
    public function redirectToGoogleAsApprover()
    {
        session(['approver_login_intent' => true]);
        return Socialite::driver('google')->redirect();
    }

    //  GOOGLE CALLBACK
    public function handleGoogleCallback()
    {
        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $provider */
            $provider = Socialite::driver('google');

            $googleUser = $provider->stateless()->user();
            $email = strtolower(trim($googleUser->email));

                        if (!str_ends_with($email, '@iiti.ac.in')) {
                return redirect('/login')->with('error', 'Only IITI emails allowed!');
            }

            //  API CALL
            $response = Http::get(
                'https://erpone.iiti.ac.in/api/method/telephone_directory.api.get_user_details',
                ['email' => $email]
            );

            $userData = $response->json()['message'] ?? null;

            //  CREATE / UPDATE USER
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            //  LOGIN
            Auth::login($user);

            // ── APPROVER LOGIN INTENT ─────────────────────────────
            if (session('approver_login_intent')) {
                session(['approver_login_intent' => null]);
                // Delegate to ApproverController for level detection & redirect
                return app(\App\Http\Controllers\ApproverController::class)->handleCallback();
            }

            // ── REGULAR USER LOGIN ────────────────────────────────
            return redirect()->intended('/dashboard');
        }
        catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login failed! ' . $e->getMessage());
        }
    }

    //  LOGOUT
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }
}