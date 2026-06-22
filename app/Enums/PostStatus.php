<?php

namespace App\Enums;

enum PostStatus: string
{
    case DRAFT = 'DRAFT';
    case PENDING = 'PENDING';
    case PUBLISHED = 'PUBLISHED';
    case SCHEDULED = 'SCHEDULED';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Rascunho',
            self::PENDING => 'Pendente',
            self::PUBLISHED => 'Publicado',
            self::SCHEDULED => 'Agendado',
        };
    }
}
