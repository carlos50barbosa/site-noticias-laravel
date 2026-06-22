<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommentStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentModerationController extends Controller
{
    public function index(): View
    {
        $comments = Comment::with('post')->latest()->limit(100)->get();

        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => CommentStatus::APPROVED]);

        return back()->with('success', 'Comentário aprovado.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => CommentStatus::REJECTED]);

        return back()->with('success', 'Comentário rejeitado.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comentário excluído.');
    }
}
