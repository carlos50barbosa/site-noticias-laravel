<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $pinned = Post::published()
            ->with(['author', 'category'])
            ->where('pinned', true)
            ->latest('updated_at')
            ->get();

        $latest = Post::published()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(13)
            ->get();

        $mostRead = Post::published()
            ->with('category')
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        return view('site.home', compact('pinned', 'latest', 'mostRead'));
    }
}
