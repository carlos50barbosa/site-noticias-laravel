<?php

namespace App\Support;

class Youtube
{
    /**
     * Extrai o ID (11 chars) de um embed/URL do YouTube presente no HTML.
     */
    public static function id(?string $html): ?string
    {
        if (! $html) {
            return null;
        }

        $pattern = '~(?:youtube\.com/embed/|youtube-nocookie\.com/embed/|youtu\.be/|[?&]v=)([A-Za-z0-9_-]{11})~';

        if (preg_match($pattern, $html, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public static function thumbnail(string $id): string
    {
        return "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
    }

    /**
     * URL de embed (privacy-enhanced). Com $autoplay para tocar ao abrir.
     */
    public static function embedUrl(string $id, bool $autoplay = false): string
    {
        $params = $autoplay ? '?autoplay=1&rel=0' : '?rel=0';

        return "https://www.youtube-nocookie.com/embed/{$id}{$params}";
    }
}
