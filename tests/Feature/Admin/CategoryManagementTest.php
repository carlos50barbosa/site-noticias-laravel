<?php

namespace Tests\Feature\Admin;

use App\Enums\PostStatus;
use App\Enums\Role;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_can_create_category_with_slug(): void
    {
        $editor = User::factory()->create(['role' => Role::EDITOR]);

        $this->actingAs($editor)->post(route('admin.categorias.store'), [
            'name' => 'Ciência & Saúde',
        ])->assertRedirect(route('admin.categorias.index'));

        $this->assertDatabaseHas('categories', ['slug' => 'ciencia-saude']);
    }

    public function test_category_with_posts_cannot_be_deleted(): void
    {
        $editor = User::factory()->create(['role' => Role::EDITOR]);
        $category = Category::create(['name' => 'Tem posts', 'slug' => 'tem-posts']);

        Post::create([
            'title' => 'X',
            'slug' => 'x',
            'content' => '<p>x</p>',
            'status' => PostStatus::DRAFT,
            'author_id' => $editor->id,
            'category_id' => $category->id,
        ]);

        $this->actingAs($editor)->delete(route('admin.categorias.destroy', $category))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
