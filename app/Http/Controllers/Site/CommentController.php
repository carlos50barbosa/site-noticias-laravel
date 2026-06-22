<?php

namespace App\Http\Controllers\Site;

use App\Enums\CommentStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    /**
     * Recebe um comentário do leitor (fica PENDENTE até a moderação).
     * Rate limit: 5 por minuto por IP.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        $key = 'comment:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()
                ->with('comment_error', 'Muitos comentários em pouco tempo. Tente novamente em instantes.')
                ->withInput();
        }

        $data = $request->validate([
            'author_name' => ['required', 'string', 'min:2', 'max:60'],
            'content' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        RateLimiter::hit($key, 60);

        $post->comments()->create([
            'author_name' => $data['author_name'],
            'content' => $data['content'],
            'status' => CommentStatus::PENDING,
        ]);

        return back()->with('comment_status', 'Comentário enviado! Ele aparecerá após aprovação da moderação.');
    }
}
