<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VpnController;
use App\Http\Controllers\WebHostingRequestController;
use App\Http\Controllers\VmRequestController;
use App\Http\Controllers\InternetAccessController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\MyRequestsController;
use Illuminate\Support\Facades\Route;

// Home / Landing page
Route::get('/', function () {
    return view('home');
});

// Login Page
Route::get('/login', function () {
    return view('welcome');
})->name('login');


// Google Login — student path (clears any approver intent flag)
Route::get('/login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Google Login — approver path (sets approver intent flag before OAuth)
Route::get('/approver-login/google', [AuthController::class, 'redirectToGoogleAsApprover']);


// User Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// VPN form
Route::get('/vpn-form', [VpnController::class, 'index'])->middleware('auth');
Route::post('/vpn-submit', [VpnController::class, 'store'])->middleware('auth');

// API se approver auto fetch
Route::get('/get-approver', [VpnController::class, 'getApprover']);

//vm-request-form
Route::get('/vm-request-application/new', [VmRequestController::class, 'create'])->middleware('auth')
    ->name('vm-requests');

//vm-request-database
Route::post('/vm-request-application', [VmRequestController::class, 'store'])->middleware('auth')
    ->name('vm-requests.store');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// VPN Success
Route::get('/vpn-success', function () {
    return view('vpn-success');
})->middleware('auth');

//web-host form
Route::get('/web-host', [WebHostingRequestController::class, 'create'])->middleware('auth');;

Route::post('/submit', [WebHostingRequestController::class, 'store'])->name('hosting.store');

// Internet Access Request form
Route::get('/internet-access', [InternetAccessController::class, 'create'])->middleware('auth')->name('internet-access.create');
Route::post('/internet-access', [InternetAccessController::class, 'store'])->middleware('auth')->name('internet-access.store');
Route::get('/internet-access/success', [InternetAccessController::class, 'success'])->middleware('auth')->name('internet-access.success');

// My Requests
Route::get('/my-requests', [MyRequestsController::class, 'index'])->middleware('auth')->name('my-requests');

// ─── APPROVER ROUTES ──────────────────────────────────────────────────────────

// Approver Login (sets session intent, shows Google login UI)
Route::get('/approver-login', [ApproverController::class, 'login'])->name('approver.login');

// Approver Dashboard & sub-pages (require auth + approver session)
Route::middleware('auth')->prefix('approver')->name('approver.')->group(function () {
    Route::get('/dashboard',  [ApproverController::class, 'dashboard'])->name('dashboard');
    Route::get('/pending',    [ApproverController::class, 'pendingRequests'])->name('pending');
    Route::get('/approved',   [ApproverController::class, 'approvedRequests'])->name('approved');
    Route::get('/rejected',   [ApproverController::class, 'rejectedRequests'])->name('rejected');

    // CITC-only routes (level 3)
    Route::get('/citc/pending',   [ApproverController::class, 'citcPending'])->name('citc.pending');
    Route::get('/citc/completed', [ApproverController::class, 'citcCompleted'])->name('citc.completed');

    // Actions
    Route::post('/approve/{type}/{id}', [ApproverController::class, 'approve'])->name('approve');
    Route::post('/reject/{type}/{id}',  [ApproverController::class, 'reject'])->name('reject');
});

