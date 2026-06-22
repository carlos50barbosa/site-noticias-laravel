<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'ADMIN';
    case EDITOR = 'EDITOR';
    case AUTHOR = 'AUTHOR';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::EDITOR => 'Editor',
            self::AUTHOR => 'Autor',
        };
    }
}
