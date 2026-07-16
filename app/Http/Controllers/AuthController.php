<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //  Google Redirect
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    //  Google Callback
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        //  EMAIL FILTER (@iiti.ac.in only)
        // if (!str_ends_with(strtolower($googleUser->email), '@iiti.ac.in')) {
        //     return redirect('/')->with('error', 'Only IIT Indore emails (@iiti.ac.in) allowed!');
        // }

        //  USER CREATE / UPDATE
        $user = User::updateOrCreate([
            'email' => $googleUser->email,
        ], [
            'name' => $googleUser->name,
            'google_id' => $googleUser->id,
            'password' => bcrypt(Str::random(16)), // random secure password
        ]);

        //  LOGIN
        Auth::login($user);

        return redirect('/dashboard');
    }

    //  LOGOUT (SECURE)
    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();      // session destroy
        request()->session()->regenerateToken(); // CSRF reset

        return redirect('/');
    }
}