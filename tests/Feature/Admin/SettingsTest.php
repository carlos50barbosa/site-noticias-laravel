<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_requires_correct_password(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN, 'password' => 'senha-correta']);

        $this->actingAs($admin)->put(route('admin.configuracoes.update'), [
            'site_name' => 'Novo Nome',
            'current_password' => 'errada',
        ])->assertSessionHasErrors('current_password');

        $this->assertDatabaseMissing('site_settings', ['site_name' => 'Novo Nome']);
    }

    public function test_admin_can_update_settings_with_password(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN, 'password' => 'senha-correta']);

        $this->actingAs($admin)->put(route('admin.configuracoes.update'), [
            'site_name' => 'Portal Atual',
            'adsense_client' => 'ca-pub-1234567890123456',
            'current_password' => 'senha-correta',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertDatabaseHas('site_settings', [
            'site_name' => 'Portal Atual',
            'adsense_client' => 'ca-pub-1234567890123456',
        ]);
    }

    public function test_ads_txt_reflects_adsense_setting(): void
    {
        $this->get('/ads.txt')->assertNotFound();

        $admin = User::factory()->create(['role' => Role::ADMIN, 'password' => 'senha-correta']);
        $this->actingAs($admin)->put(route('admin.configuracoes.update'), [
            'site_name' => 'Portal',
            'adsense_client' => 'ca-pub-1234567890123456',
            'current_password' => 'senha-correta',
        ]);

        $this->get('/ads.txt')->assertOk()->assertSee('pub-1234567890123456');
    }

    public function test_editor_cannot_access_settings(): void
    {
        $editor = User::factory()->create(['role' => Role::EDITOR]);

        $this->actingAs($editor)->get(route('admin.configuracoes.edit'))->assertForbidden();
    }
}
