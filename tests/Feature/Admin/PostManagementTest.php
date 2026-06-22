<?php

namespace Tests\Feature\Admin;

use App\Enums\PostStatus;
use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_and_content_is_sanitized(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($admin)->post('/admin/noticias', [
            'title' => 'Notícia do Admin',
            'content' => '<p>conteúdo ok</p><script>alert(1)</script>',
            'status' => 'PUBLISHED',
            'tags' => 'Política, Economia, Política',
        ])->assertRedirect(route('admin.dashboard'));

        $post = Post::firstOrFail();

        $this->assertSame(PostStatus::PUBLISHED, $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertNotNull($post->slug);
        $this->assertStringContainsString('conteúdo ok', $post->content);
        $this->assertStringNotContainsString('<script', $post->content);
        $this->assertSame(2, $post->tags()->count());
    }

    public function test_author_publish_is_downgraded_to_pending(): void
    {
        $author = User::factory()->create(['role' => Role::AUTHOR]);

        $this->actingAs($author)->post('/admin/noticias', [
            'title' => 'Tentativa do Autor',
            'content' => '<p>texto</p>',
            'status' => 'PUBLISHED',
            'pinned' => '1',
        ])->assertRedirect();

        $post = Post::firstOrFail();

        $this->assertSame(PostStatus::PENDING, $post->status);
        $this->assertNull($post->published_at);
        $this->assertFalse($post->pinned); // autor não fixa
        $this->assertSame($author->id, $post->author_id);
    }

    public function test_author_cannot_edit_another_authors_post(): void
    {
        $author = User::factory()->create(['role' => Role::AUTHOR]);
        $other = User::factory()->create(['role' => Role::AUTHOR]);

        $post = Post::create([
            'title' => 'De outro autor',
            'slug' => 'de-outro-autor',
            'content' => '<p>x</p>',
            'status' => PostStatus::DRAFT,
            'author_id' => $other->id,
        ]);

        $this->actingAs($author)->get("/admin/noticias/{$post->id}/editar")->assertForbidden();
    }
}
