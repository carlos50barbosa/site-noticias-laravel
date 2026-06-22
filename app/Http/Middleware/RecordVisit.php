<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordVisit
{
    /**
     * Conta uma visita ao site (apenas em GET de páginas públicas).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && ! $request->ajax()) {
            SiteSetting::query()->where('id', 1)->increment('visits');
        }

        return $response;
    }
}
