<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommentStatus;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function index(): View
    {
        return view('admin.stats', [
            'visits' => SiteSetting::current()->visits,
            'totalReads' => (int) Post::sum('view_count'),
            'published' => Post::where('status', PostStatus::PUBLISHED)->count(),
            'totalPosts' => Post::count(),
            'commentsTotal' => Comment::count(),
            'commentsPending' => Comment::where('status', CommentStatus::PENDING)->count(),
            'topPosts' => Post::published()->orderByDesc('view_count')->take(5)->get(['id', 'title', 'slug', 'view_count']),
        ]);
    }
}
