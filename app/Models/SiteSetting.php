<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['site_name', 'logo_url', 'favicon_url', 'adsense_client', 'visits'])]
class SiteSetting extends Model
{
    protected static ?self $current = null;

    protected function casts(): array
    {
        return [
            'visits' => 'integer',
        ];
    }

    /**
     * Configurações do site (linha única, id = 1), memoizadas por requisição.
     */
    public static function current(): self
    {
        return static::$current ??= static::firstOrCreate(
            ['id' => 1],
            ['site_name' => config('app.name', 'Site de Notícias')],
        );
    }
}

