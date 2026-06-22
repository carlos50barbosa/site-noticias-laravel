<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdClick;
use Illuminate\Http\RedirectResponse;

class AdClickController extends Controller
{
    /**
     * Conta o clique (e registra o evento para o relatório) e redireciona ao destino.
     */
    public function click(Ad $ad): RedirectResponse
    {
        Ad::whereKey($ad->id)->increment('clicks');
        AdClick::create(['ad_id' => $ad->id]);

        return redirect()->away($ad->link_url);
    }
}
