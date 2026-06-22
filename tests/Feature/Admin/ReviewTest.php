<?php

namespace Tests\Feature\Admin;

use App\Enums\PostStatus;
use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function pendingPost(): Post
    {
        $author = User::factory()->create(['role' => Role::AUTHOR]);

        return Post::create([
            'title' => 'Para revisão',
            'slug' => 'para-revisao',
            'content' => '<p>x</p>',
            'status' => PostStatus::PENDING,
            'author_id' => $author->id,
        ]);
    }

    public function test_editor_can_publish_pending_post(): void
    {
        $editor = User::factory()->create(['role' => Role::EDITOR]);
        $post = $this->pendingPost();

        $this->actingAs($editor)->post(route('admin.revisao.approve', $post))->assertRedirect();

        $post->refresh();
        $this->assertSame(PostStatus::PUBLISHED, $post->status);
        $this->assertNotNull($post->published_at);
    }

    public function test_editor_can_return_post_with_note(): void
    {
        $editor = User::factory()->create(['role' => Role::EDITOR]);
        $post = $this->pendingPost();

        $this->actingAs($editor)->post(route('admin.revisao.return', $post), [
            'note' => 'Ajuste o título.',
        ])->assertRedirect();

        $post->refresh();
        $this->assertSame(PostStatus::DRAFT, $post->status);
        $this->assertSame('Ajuste o título.', $post->review_note);
    }

    public function test_author_cannot_access_review_actions(): void
    {
        $author = User::factory()->create(['role' => Role::AUTHOR]);
        $post = $this->pendingPost();

        $this->actingAs($author)->post(route('admin.revisao.approve', $post))->assertForbidden();
    }
}
