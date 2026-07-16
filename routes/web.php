<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VpnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Google Login
Route::get('/login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// VPN
Route::get('/vpn-form', [VpnController::class, 'index'])->middleware('auth');
Route::post('/vpn-submit', [VpnController::class, 'store'])->middleware('auth');

// Success Page
Route::get('/vpn-success', function () {
    return view('vpn-success');
})->middleware('auth');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');