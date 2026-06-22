<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    private function user(Role $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_author_sees_own_posts_list_but_not_managed_areas(): void
    {
        $author = $this->user(Role::AUTHOR);

        $this->actingAs($author)->get('/admin')->assertOk();
        $this->actingAs($author)->get('/admin/categorias')->assertForbidden();
        $this->actingAs($author)->get('/admin/usuarios')->assertForbidden();
        $this->actingAs($author)->get('/admin/revisao')->assertForbidden();
    }

    public function test_editor_can_access_categories_and_review_but_not_users(): void
    {
        $editor = $this->user(Role::EDITOR);

        $this->actingAs($editor)->get('/admin/categorias')->assertOk();
        $this->actingAs($editor)->get('/admin/revisao')->assertOk();
        $this->actingAs($editor)->get('/admin/usuarios')->assertForbidden();
    }

    public function test_admin_can_access_everything(): void
    {
        $admin = $this->user(Role::ADMIN);

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/categorias')->assertOk();
        $this->actingAs($admin)->get('/admin/usuarios')->assertOk();
        $this->actingAs($admin)->get('/admin/revisao')->assertOk();
        $this->actingAs($admin)->get('/admin/noticias/nova')->assertOk();
    }
}
