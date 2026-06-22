<?php

namespace Tests\Feature\Site;

use App\Enums\PostStatus;
use App\Enums\Role;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    private function publishedPost(array $overrides = []): Post
    {
        $author = User::factory()->create(['role' => Role::ADMIN]);
        $category = Category::create(['name' => 'Tecnologia', 'slug' => 'tecnologia']);

        return Post::create(array_merge([
            'title' => 'Notícia de Teste',
            'slug' => 'noticia-de-teste',
            'excerpt' => 'Um resumo de teste.',
            'content' => '<p>Conteúdo da notícia de teste.</p>',
            'status' => PostStatus::PUBLISHED,
            'published_at' => now(),
            'author_id' => $author->id,
            'category_id' => $category->id,
        ], $overrides));
    }

    public function test_home_renders(): void
    {
        $this->publishedPost();

        $this->get('/')->assertOk()->assertSee('Notícia de Teste');
    }

    public function test_draft_is_not_listed_on_home(): void
    {
        $this->publishedPost([
            'title' => 'Rascunho Oculto',
            'slug' => 'rascunho-oculto',
            'status' => PostStatus::DRAFT,
            'published_at' => null,
        ]);

        $this->get('/')->assertOk()->assertDontSee('Rascunho Oculto');
    }

    public function test_article_renders_increments_views_and_has_json_ld(): void
    {
        $post = $this->publishedPost(['view_count' => 0]);

        $response = $this->get('/noticia/'.$post->slug);

        $response->assertOk()
            ->assertSee('Conteúdo da notícia de teste.', false)
            ->assertSee('NewsArticle', false);

        $this->assertSame(1, $post->fresh()->view_count);
    }

    public function test_unpublished_article_returns_404(): void
    {
        $post = $this->publishedPost([
            'slug' => 'oculto',
            'status' => PostStatus::DRAFT,
            'published_at' => null,
        ]);

        $this->get('/noticia/'.$post->slug)->assertNotFound();
    }

    public function test_category_page_renders(): void
    {
        $post = $this->publishedPost();

        $this->get('/categoria/'.$post->category->slug)->assertOk()->assertSee('Notícia de Teste');
    }

    public function test_search_finds_published_post(): void
    {
        $this->publishedPost();

        $this->get('/busca?q=Teste')->assertOk()->assertSee('Notícia de Teste');
    }

    public function test_sitemap_robots_and_feed(): void
    {
        $this->publishedPost();

        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin');
        $this->get('/feed.xml')->assertOk()->assertSee('Notícia de Teste');
    }
}
