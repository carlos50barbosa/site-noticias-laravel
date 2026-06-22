<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchedulingTest extends TestCase
{
    use RefreshDatabase;

    public function test_due_scheduled_posts_are_published(): void
    {
        $author = User::factory()->create(['role' => Role::ADMIN]);

        $due = Post::create([
            'title' => 'Agendada vencida',
            'slug' => 'agendada-vencida',
            'content' => '<p>x</p>',
            'status' => PostStatus::SCHEDULED,
            'published_at' => now()->subMinute(),
            'author_id' => $author->id,
        ]);

        $future = Post::create([
            'title' => 'Agendada futura',
            'slug' => 'agendada-futura',
            'content' => '<p>x</p>',
            'status' => PostStatus::SCHEDULED,
            'published_at' => now()->addDay(),
            'author_id' => $author->id,
        ]);

        $this->artisan('posts:publish-scheduled')->assertSuccessful();

        $this->assertSame(PostStatus::PUBLISHED, $due->fresh()->status);
        $this->assertSame(PostStatus::SCHEDULED, $future->fresh()->status);
    }
}
