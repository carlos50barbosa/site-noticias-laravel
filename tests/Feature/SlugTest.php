<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Support\Slug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_accent_free_slug(): void
    {
        $this->assertSame('eleicoes-2024', Slug::unique('categories', 'Eleições 2024!'));
    }

    public function test_appends_suffix_on_conflict(): void
    {
        Category::create(['name' => 'Esportes', 'slug' => 'esportes']);

        $this->assertSame('esportes-2', Slug::unique('categories', 'Esportes'));
    }

    public function test_ignores_given_id(): void
    {
        $category = Category::create(['name' => 'Esportes', 'slug' => 'esportes']);

        $this->assertSame('esportes', Slug::unique('categories', 'Esportes', $category->id));
    }
}
