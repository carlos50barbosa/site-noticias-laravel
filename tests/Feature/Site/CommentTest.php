<?php

namespace Tests\Feature\Site;

use App\Enums\CommentStatus;
use App\Enums\PostStatus;
use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private function publishedPost(): Post
    {
        $author = User::factory()->create(['role' => Role::ADMIN]);

        return Post::create([
            'title' => 'Notícia',
            'slug' => 'noticia',
            'content' => '<p>x</p>',
            'status' => PostStatus::PUBLISHED,
            'published_at' => now(),
            'author_id' => $author->id,
        ]);
    }

    public function test_visitor_can_submit_comment_as_pending(): void
    {
        $post = $this->publishedPost();

        $this->post("/noticia/{$post->id}/comentarios", [
            'author_name' => 'João',
            'content' => 'Excelente reportagem!',
        ])->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'author_name' => 'João',
            'status' => 'PENDING',
        ]);
    }

    public function test_only_approved_comments_are_shown(): void
    {
        $post = $this->publishedPost();
        $post->comments()->create(['author_name' => 'A', 'content' => 'comentario-aprovado', 'status' => CommentStatus::APPROVED]);
        $post->comments()->create(['author_name' => 'B', 'content' => 'comentario-pendente', 'status' => CommentStatus::PENDING]);

        $this->get("/noticia/{$post->slug}")
            ->assertSee('comentario-aprovado')
            ->assertDontSee('comentario-pendente');
    }
}
