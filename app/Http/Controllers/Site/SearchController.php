<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $posts = null;

        if (mb_strlen($query) >= 2) {
            $posts = $this->search($query);
        }

        return view('site.search', compact('query', 'posts'));
    }

    /**
     * @return LengthAwarePaginator<int, Post>
     */
    private function search(string $query): LengthAwarePaginator
    {
        $like = '%'.$query.'%';

        return Post::published()
            ->with(['author', 'category'])
            ->where(function ($where) use ($like) {
                $where->where('title', 'like', $like)
                    ->orWhere('excerpt', 'like', $like)
                    ->orWhere('content', 'like', $like);
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();
    }
}
