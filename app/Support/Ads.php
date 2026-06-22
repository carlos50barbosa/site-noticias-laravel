<?php

namespace App\Support;

use App\Enums\AdPlacement;
use App\Models\Ad;

class Ads
{
    /**
     * Seleciona um anúncio ativo e vigente para a posição e conta a impressão.
     */
    public static function pick(AdPlacement $placement): ?Ad
    {
        $ad = Ad::query()
            ->where('active', true)
            ->where('placement', $placement)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->inRandomOrder()
            ->first();

        if ($ad) {
            Ad::whereKey($ad->id)->increment('impressions');
        }

        return $ad;
    }
}
