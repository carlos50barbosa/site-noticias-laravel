<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\Audit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $posts = Post::with(['author', 'category'])
            ->where('status', PostStatus::PENDING)
            ->latest('updated_at')
            ->get();

        return view('admin.review.index', compact('posts'));
    }

    public function approve(Post $post): RedirectResponse
    {
        $post->update([
            'status' => PostStatus::PUBLISHED,
            'published_at' => $post->published_at ?? now(),
            'review_note' => null,
        ]);

        Audit::log('post.publish', 'post', $post->id);

        return back()->with('success', 'Notícia publicada.');
    }

    public function sendBack(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        $post->update([
            'status' => PostStatus::DRAFT,
            'review_note' => $data['note'],
        ]);

        Audit::log('post.return', 'post', $post->id);

        return back()->with('success', 'Notícia devolvida ao autor com o comentário.');
    }
}
