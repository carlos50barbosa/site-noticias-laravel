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
}
