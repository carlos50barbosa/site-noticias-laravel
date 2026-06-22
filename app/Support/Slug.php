<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Slug
{
    /**
     * Gera um slug único para a tabela (acrescenta -2, -3... em caso de conflito).
     */
    public static function unique(string $table, string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);

        if ($base === '') {
            $base = 'item';
        }

        $slug = $base;
        $suffix = 2;

        while (
            DB::table($table)
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
