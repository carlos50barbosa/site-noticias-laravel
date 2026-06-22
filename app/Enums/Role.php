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

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function canManageUsers(): bool
    {
        return $this === self::ADMIN;
    }

    public function canManageCategories(): bool
    {
        return $this === self::ADMIN || $this === self::EDITOR;
    }

    public function canPublish(): bool
    {
        return $this === self::ADMIN || $this === self::EDITOR;
    }

    public function canManageAllPosts(): bool
    {
        return $this === self::ADMIN || $this === self::EDITOR;
    }
}
