<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentModerationController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Site\AdClickController;
use App\Http\Controllers\Site\AdsTxtController;
use App\Http\Controllers\Site\AuthorController;
use App\Http\Controllers\Site\CategoryController as SiteCategoryController;
use App\Http\Controllers\Site\CommentController;
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
    Route::get('/categoria/{slug}', [SiteCategoryController::class, 'show'])->name('categoria');
    Route::get('/tag/{slug}', [TagController::class, 'show'])->name('tag');
    Route::get('/autor/{user}', [AuthorController::class, 'show'])->name('autor');
    Route::get('/busca', [SearchController::class, 'index'])->name('busca');
});

// Interações públicas (sem contagem de visita)
Route::post('/noticia/{post}/comentarios', [CommentController::class, 'store'])->name('comentarios.store');
Route::get('/ads/{ad}/click', [AdClickController::class, 'click'])->name('ads.click');

// SEO / feeds
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', [RobotsController::class, 'index']);
Route::get('/feed.xml', [FeedController::class, 'index']);
Route::get('/ads.txt', [AdsTxtController::class, 'index']);

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

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('upload', [UploadController::class, 'store'])->name('upload');

        // Notícias (qualquer autenticado; AUTHOR vê/edita só as próprias)
        Route::get('/', [AdminPostController::class, 'index'])->name('dashboard');
        Route::get('noticias/nova', [AdminPostController::class, 'create'])->name('noticias.create');
        Route::post('noticias', [AdminPostController::class, 'store'])->name('noticias.store');
        Route::get('noticias/{post}/editar', [AdminPostController::class, 'edit'])->name('noticias.edit');
        Route::put('noticias/{post}', [AdminPostController::class, 'update'])->name('noticias.update');
        Route::delete('noticias/{post}', [AdminPostController::class, 'destroy'])->name('noticias.destroy');

        // Fila de revisão (EDITOR/ADMIN)
        Route::middleware('can:publish-posts')->group(function () {
            Route::get('revisao', [ReviewController::class, 'index'])->name('revisao');
            Route::post('revisao/{post}/aprovar', [ReviewController::class, 'approve'])->name('revisao.approve');
            Route::post('revisao/{post}/devolver', [ReviewController::class, 'sendBack'])->name('revisao.return');
        });

        // Categorias (EDITOR/ADMIN)
        Route::middleware('can:manage-categories')->group(function () {
            Route::get('categorias', [CategoryController::class, 'index'])->name('categorias.index');
            Route::post('categorias', [CategoryController::class, 'store'])->name('categorias.store');
            Route::get('categorias/{category}/editar', [CategoryController::class, 'edit'])->name('categorias.edit');
            Route::put('categorias/{category}', [CategoryController::class, 'update'])->name('categorias.update');
            Route::delete('categorias/{category}', [CategoryController::class, 'destroy'])->name('categorias.destroy');
        });

        // Usuários (ADMIN)
        Route::middleware('can:manage-users')->group(function () {
            Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.index');
            Route::post('usuarios', [UserController::class, 'store'])->name('usuarios.store');
            Route::get('usuarios/{user}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
            Route::put('usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
            Route::delete('usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
        });

        // Comentários (moderação) e estatísticas (EDITOR/ADMIN)
        Route::middleware('can:manage-all-posts')->group(function () {
            Route::get('comentarios', [CommentModerationController::class, 'index'])->name('comentarios.index');
            Route::post('comentarios/{comment}/aprovar', [CommentModerationController::class, 'approve'])->name('comentarios.approve');
            Route::post('comentarios/{comment}/rejeitar', [CommentModerationController::class, 'reject'])->name('comentarios.reject');
            Route::delete('comentarios/{comment}', [CommentModerationController::class, 'destroy'])->name('comentarios.destroy');
            Route::get('estatisticas', [StatsController::class, 'index'])->name('estatisticas');
        });

        // Publicidades (ADMIN)
        Route::middleware('can:manage-ads')->group(function () {
            Route::get('publicidades', [AdController::class, 'index'])->name('publicidades.index');
            Route::get('publicidades/nova', [AdController::class, 'create'])->name('publicidades.create');
            Route::post('publicidades', [AdController::class, 'store'])->name('publicidades.store');
            Route::get('publicidades/{ad}/editar', [AdController::class, 'edit'])->name('publicidades.edit');
            Route::put('publicidades/{ad}', [AdController::class, 'update'])->name('publicidades.update');
            Route::delete('publicidades/{ad}', [AdController::class, 'destroy'])->name('publicidades.destroy');
            Route::get('publicidades/{ad}/relatorio', [AdController::class, 'report'])->name('publicidades.report');
        });

        // Configurações (ADMIN)
        Route::middleware('can:manage-settings')->group(function () {
            Route::get('configuracoes', [SettingsController::class, 'edit'])->name('configuracoes.edit');
            Route::put('configuracoes', [SettingsController::class, 'update'])->name('configuracoes.update');
        });

        // Logs de auditoria (ADMIN)
        Route::middleware('can:view-audit-logs')->group(function () {
            Route::get('logs', [LogController::class, 'index'])->name('logs');
        });
    });
});
