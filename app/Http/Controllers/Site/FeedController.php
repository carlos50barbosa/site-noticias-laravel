<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function index(): Response
    {
        $esc = fn ($value) => htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        $settings = SiteSetting::current();
        $posts = Post::published()->with('category')->latest('published_at')->take(50)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<rss version="2.0"><channel>'."\n";
        $xml .= '<title>'.$esc($settings->site_name).'</title>'."\n";
        $xml .= '<link>'.$esc(route('home')).'</link>'."\n";
        $xml .= '<description>'.$esc('Últimas notícias de '.$settings->site_name).'</description>'."\n";
        $xml .= '<language>pt-BR</language>'."\n";

        foreach ($posts as $post) {
            $url = route('noticia', $post->slug);
            $xml .= '<item>';
            $xml .= '<title>'.$esc($post->title).'</title>';
            $xml .= '<link>'.$esc($url).'</link>';
            $xml .= '<guid>'.$esc($url).'</guid>';
            $xml .= '<description>'.$esc($post->excerptText(300)).'</description>';
            if ($post->published_at) {
                $xml .= '<pubDate>'.$esc($post->published_at->toRssString()).'</pubDate>';
            }
            if ($post->category) {
                $xml .= '<category>'.$esc($post->category->name).'</category>';
            }
            $xml .= '</item>'."\n";
        }

        $xml .= '</channel></rss>';

        return response($xml)->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
