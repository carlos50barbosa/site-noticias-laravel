<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtrasAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_can_moderate_and_see_stats_only(): void
    {
        $editor = User::factory()->create(['role' => Role::EDITOR]);

        $this->actingAs($editor)->get(route('admin.comentarios.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.estatisticas'))->assertOk();
        $this->actingAs($editor)->get(route('admin.publicidades.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.configuracoes.edit'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.logs'))->assertForbidden();
    }

    public function test_admin_can_access_all_extras(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($admin)->get(route('admin.comentarios.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.estatisticas'))->assertOk();
        $this->actingAs($admin)->get(route('admin.publicidades.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.configuracoes.edit'))->assertOk();
        $this->actingAs($admin)->get(route('admin.logs'))->assertOk();
    }
}
