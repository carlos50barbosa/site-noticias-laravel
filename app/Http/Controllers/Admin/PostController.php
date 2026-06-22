<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Support\Audit;
use App\Support\Sanitizer;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Post::with(['author', 'category'])->latest('updated_at');

        if (! $user->canManageAllPosts()) {
            $query->where('author_id', $user->id);
        } elseif ($request->filled('author')) {
            $query->where('author_id', $request->integer('author'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.posts.index', [
            'posts' => $query->paginate(20)->withQueryString(),
            'authors' => $user->canManageAllPosts() ? User::orderBy('name')->get(['id', 'name']) : collect(),
            'statuses' => PostStatus::cases(),
            'filterStatus' => $request->input('status'),
            'filterAuthor' => $request->integer('author'),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        return view('admin.posts.form', [
            'post' => new Post,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $post = new Post;
        $post->author_id = $request->user()->id;
        $this->save($post, $request);

        Audit::log('post.create', 'post', $post->id);

        return redirect()->route('admin.dashboard')->with('success', 'Notícia salva.');
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        return view('admin.posts.form', [
            'post' => $post->load('tags'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $this->save($post, $request);

        Audit::log('post.update', 'post', $post->id);

        return redirect()->route('admin.dashboard')->with('success', 'Notícia atualizada.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        Audit::log('post.delete', 'post', $post->id);

        return redirect()->route('admin.dashboard')->with('success', 'Notícia excluída.');
    }

    /**
     * Persiste a notícia aplicando regras de papel, slug, sanitização e tags.
     */
    private function save(Post $post, PostRequest $request): void
    {
        $data = $request->validated();
        $user = $request->user();

        // AUTHOR não publica nem agenda: vira PENDING (envio para revisão).
        $status = PostStatus::from($data['status']);
        if (! $user->canPublish() && in_array($status, [PostStatus::PUBLISHED, PostStatus::SCHEDULED], true)) {
            $status = PostStatus::PENDING;
        }

        $publishedAt = match ($status) {
            PostStatus::PUBLISHED => $post->published_at ?? now(),
            PostStatus::SCHEDULED => $data['scheduled_at'],
            default => null,
        };

        $post->fill([
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => Sanitizer::clean($data['content']),
            'cover_image_url' => $data['cover_image_url'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'status' => $status,
            'pinned' => $user->canPublish() ? $request->boolean('pinned') : false,
            'published_at' => $publishedAt,
            'review_note' => null,
        ]);

        $post->slug = Slug::unique('posts', $data['title'], $post->id);
        $post->save();

        $post->tags()->sync($this->resolveTags($data['tags'] ?? null));
    }

    /**
     * Converte a string de tags (separadas por vírgula) em IDs (upsert por slug).
     *
     * @return array<int, int>
     */
    private function resolveTags(?string $raw): array
    {
        if (! $raw) {
            return [];
        }

        return collect(explode(',', $raw))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->take(12)
            ->map(fn ($name) => Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            )->id)
            ->values()
            ->all();
    }
}
