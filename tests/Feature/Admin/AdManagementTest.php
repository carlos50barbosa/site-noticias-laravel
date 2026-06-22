<?php

namespace Tests\Feature\Admin;

use App\Enums\AdPlacement;
use App\Enums\Role;
use App\Models\Ad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_ad(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($admin)->post(route('admin.publicidades.store'), [
            'title' => 'Parceiro',
            'image_url' => '/uploads/banner.png',
            'link_url' => 'https://parceiro.test',
            'placement' => 'HOME',
            'active' => '1',
        ])->assertRedirect(route('admin.publicidades.index'));

        $this->assertDatabaseHas('ads', ['title' => 'Parceiro', 'placement' => 'HOME', 'active' => 1]);
    }

    public function test_click_increments_and_redirects(): void
    {
        $ad = Ad::create([
            'title' => 'X',
            'image_url' => '/uploads/x.png',
            'link_url' => 'https://destino.test',
            'placement' => AdPlacement::SITEWIDE,
        ]);

        $this->get(route('ads.click', $ad))->assertRedirect('https://destino.test');

        $this->assertSame(1, $ad->fresh()->clicks);
        $this->assertDatabaseCount('ad_clicks', 1);
    }

    public function test_report_renders_for_admin(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $ad = Ad::create([
            'title' => 'X',
            'image_url' => '/uploads/x.png',
            'link_url' => 'https://destino.test',
            'placement' => AdPlacement::HOME,
        ]);

        $this->actingAs($admin)->get(route('admin.publicidades.report', $ad))->assertOk();
    }

    public function test_editor_cannot_manage_ads(): void
    {
        $editor = User::factory()->create(['role' => Role::EDITOR]);

        $this->actingAs($editor)->get(route('admin.publicidades.index'))->assertForbidden();
    }
}
