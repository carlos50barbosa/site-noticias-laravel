<?php

namespace App\Providers;

use App\Enums\CommentStatus;
use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurações do site + categorias (nav do header) em todas as views públicas.
        View::composer(['layouts.public', 'site.*'], function ($view) {
            $view->with('settings', SiteSetting::current());
            $view->with('navCategories', Category::orderBy('name')->get());
        });

        // Badges de pendências (revisão e comentários) na sidebar do admin.
        View::composer('layouts.admin', function ($view) {
            $view->with('pendingReviewCount', Post::where('status', PostStatus::PENDING)->count());
            $view->with('pendingCommentsCount', Comment::where('status', CommentStatus::PENDING)->count());
        });

        // Gates de papel (verificados no servidor em toda ação).
        Gate::define('manage-users', fn (User $user) => $user->canManageUsers());
        Gate::define('manage-categories', fn (User $user) => $user->canManageCategories());
        Gate::define('publish-posts', fn (User $user) => $user->canPublish());
        Gate::define('manage-all-posts', fn (User $user) => $user->canManageAllPosts());
        Gate::define('manage-ads', fn (User $user) => $user->isAdmin());
        Gate::define('manage-settings', fn (User $user) => $user->isAdmin());
        Gate::define('view-audit-logs', fn (User $user) => $user->isAdmin());

        // E-mail de redefinição de senha em português (link aponta para /admin/redefinir).
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            return (new MailMessage)
                ->subject('Redefinição de senha')
                ->greeting('Olá!')
                ->line('Você está recebendo este e-mail porque pedimos a redefinição da senha da sua conta.')
                ->action('Redefinir senha', $url)
                ->line('O link expira em 60 minutos.')
                ->line('Se você não solicitou, ignore este e-mail.')
                ->salutation('Atenciosamente, '.config('app.name'));
        });
    }
}
