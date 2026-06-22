<?php

namespace App\Models;

use App\Enums\AdPlacement;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'image_url',
    'link_url',
    'placement',
    'active',
    'starts_at',
    'ends_at',
    'impressions',
    'clicks',
])]
class Ad extends Model
{
    protected function casts(): array
    {
        return [
            'placement' => AdPlacement::class,
            'active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'impressions' => 'integer',
            'clicks' => 'integer',
        ];
    }

    /**
     * @return HasMany<AdClick, $this>
     */
    public function clickEvents(): HasMany
    {
        return $this->hasMany(AdClick::class);
    }
}
