<?php

namespace App\Support;

class Html
{
    /**
     * Remove tags HTML e normaliza espaços (para resumos/og:description).
     */
    public static function strip(string $html): string
    {
        $text = strip_tags($html);
        $text = preg_replace('/\s+/', ' ', $text ?? '');

        return trim($text ?? '');
    }
}
