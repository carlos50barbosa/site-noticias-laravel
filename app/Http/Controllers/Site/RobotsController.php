<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /api',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain');
    }
}
