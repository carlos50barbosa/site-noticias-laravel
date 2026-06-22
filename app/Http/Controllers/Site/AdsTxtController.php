<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Response;

class AdsTxtController extends Controller
{
    public function index(): Response
    {
        $client = SiteSetting::current()->adsense_client;

        abort_if(! $client, 404);

        // ca-pub-XXXX -> pub-XXXX (formato do ads.txt)
        $publisher = str_replace('ca-pub-', 'pub-', $client);

        return response("google.com, {$publisher}, DIRECT, f08c47fec0942fa0\n")
            ->header('Content-Type', 'text/plain');
    }
}
