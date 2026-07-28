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
    //  GOOGLE REDIRECT
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    //  GOOGLE CALLBACK
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $email = strtolower(trim($googleUser->email));

            //  ONLY IITI EMAIL ALLOWED
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

          

//  VERY IMPORTANT
session()->forget('url.intended');

if ($userData) {
    return redirect('/approver-dashboard');
}

return redirect('/dashboard');
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