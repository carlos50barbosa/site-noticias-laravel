<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Site\AuthorController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\FeedController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PostController;
use App\Http\Controllers\Site\RobotsController;
use App\Http\Controllers\Site\SearchController;
use App\Http\Controllers\Site\SitemapController;
use App\Http\Controllers\Site\TagController;
use App\Http\Middleware\RecordVisit;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Site público (SSR) — conta visitas
// ---------------------------------------------------------------------------
Route::middleware(RecordVisit::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/noticia/{slug}', [PostController::class, 'show'])->name('noticia');
    Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('categoria');
    Route::get('/tag/{slug}', [TagController::class, 'show'])->name('tag');
    Route::get('/autor/{user}', [AuthorController::class, 'show'])->name('autor');
    Route::get('/busca', [SearchController::class, 'index'])->name('busca');
});

// SEO / feeds (sem contagem de visita)
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', [RobotsController::class, 'index']);
Route::get('/feed.xml', [FeedController::class, 'index']);

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
