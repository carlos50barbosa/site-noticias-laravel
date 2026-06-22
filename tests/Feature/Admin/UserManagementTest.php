<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($admin)->post('/admin/usuarios', [
            'name' => 'Novo Editor',
            'email' => 'editor@example.com',
            'password' => 'senha-segura',
            'role' => 'EDITOR',
        ])->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('users', ['email' => 'editor@example.com', 'role' => 'EDITOR']);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($admin)->delete(route('admin.usuarios.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_demote_own_role(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($admin)->put(route('admin.usuarios.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'EDITOR',
        ])->assertSessionHas('error');

        $this->assertSame(Role::ADMIN, $admin->refresh()->role);
    }
}
