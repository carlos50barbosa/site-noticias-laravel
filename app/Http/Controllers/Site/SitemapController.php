<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $esc = fn ($value) => htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        $posts = Post::published()->select('slug', 'updated_at')->latest('published_at')->get();
        $categories = Category::select('slug', 'updated_at')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        $xml .= '  <url><loc>'.$esc(route('home')).'</loc>'
            .'<changefreq>hourly</changefreq><priority>1.0</priority></url>'."\n";

        foreach ($categories as $category) {
            $xml .= '  <url><loc>'.$esc(route('categoria', $category->slug)).'</loc>'
                .'<lastmod>'.$esc($category->updated_at?->toAtomString()).'</lastmod>'
                .'<changefreq>daily</changefreq><priority>0.6</priority></url>'."\n";
        }

        foreach ($posts as $post) {
            $xml .= '  <url><loc>'.$esc(route('noticia', $post->slug)).'</loc>'
                .'<lastmod>'.$esc($post->updated_at?->toAtomString()).'</lastmod>'
                .'<changefreq>weekly</changefreq><priority>0.7</priority></url>'."\n";
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'application/xml');
    }
}
