<?php

namespace App\Enums;

enum AdPlacement: string
{
    case SITEWIDE = 'SITEWIDE';
    case HOME = 'HOME';
    case ARTICLE = 'ARTICLE';

    public function label(): string
    {
        return match ($this) {
            self::SITEWIDE => 'Todo o site',
            self::HOME => 'Página inicial',
            self::ARTICLE => 'Dentro das notícias',
        };
    }
}
