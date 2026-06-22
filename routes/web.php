<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ---------------------------------------------------------------------------
// Autenticação do painel (sessão) — apenas visitantes não logados
// ---------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/admin/esqueci', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/admin/esqueci', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/admin/redefinir/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/admin/redefinir', [NewPasswordController::class, 'store'])->name('password.store');
});

// ---------------------------------------------------------------------------
// Painel administrativo — exige sessão autenticada
// ---------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
});
