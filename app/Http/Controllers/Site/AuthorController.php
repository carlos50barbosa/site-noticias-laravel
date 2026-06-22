<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show(User $user): View
    {
        $posts = Post::published()
            ->with('category')
            ->where('author_id', $user->id)
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('site.author', ['author' => $user, 'posts' => $posts]);
    }
}
