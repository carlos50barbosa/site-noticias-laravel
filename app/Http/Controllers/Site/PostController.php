<?php

namespace App\Http\Controllers\Site;

use App\Enums\CommentStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(string $slug): View
    {
        $post = Post::published()
            ->with(['author', 'category', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Contador de visualizações (não altera updated_at).
        Post::whereKey($post->id)->increment('view_count');

        $related = $post->category_id
            ? Post::published()
                ->with('category')
                ->where('category_id', $post->category_id)
                ->whereKeyNot($post->id)
                ->latest('published_at')
                ->take(3)
                ->get()
            : new Collection();

        $comments = $post->comments()
            ->where('status', CommentStatus::APPROVED)
            ->latest()
            ->get();

        return view('site.post', compact('post', 'related', 'comments'));
    }
}
