<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdRequest;
use App\Models\Ad;
use App\Models\AdClick;
use App\Support\Audit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AdController extends Controller
{
    public function index(): View
    {
        $ads = Ad::latest()->get();

        return view('admin.ads.index', compact('ads'));
    }

    public function create(): View
    {
        return view('admin.ads.form', ['ad' => new Ad(['active' => true])]);
    }

    public function store(AdRequest $request): RedirectResponse
    {
        $ad = Ad::create($this->data($request));

        Audit::log('ad.create', 'ad', $ad->id);

        return redirect()->route('admin.publicidades.index')->with('success', 'Anúncio criado.');
    }

    public function edit(Ad $ad): View
    {
        return view('admin.ads.form', compact('ad'));
    }

    public function update(AdRequest $request, Ad $ad): RedirectResponse
    {
        $ad->update($this->data($request));

        Audit::log('ad.update', 'ad', $ad->id);

        return redirect()->route('admin.publicidades.index')->with('success', 'Anúncio atualizado.');
    }

    public function destroy(Ad $ad): RedirectResponse
    {
        $ad->delete();

        Audit::log('ad.delete', 'ad', $ad->id);

        return redirect()->route('admin.publicidades.index')->with('success', 'Anúncio excluído.');
    }

    public function report(Ad $ad): View
    {
        $now = now();

        $today = AdClick::where('ad_id', $ad->id)->where('created_at', '>=', $now->copy()->startOfDay())->count();
        $last7 = AdClick::where('ad_id', $ad->id)->where('created_at', '>=', $now->copy()->subDays(7))->count();
        $last30 = AdClick::where('ad_id', $ad->id)->where('created_at', '>=', $now->copy()->subDays(30))->count();
        $total = AdClick::where('ad_id', $ad->id)->count();
        $ctr = $ad->impressions > 0 ? round($total / $ad->impressions * 100, 2) : 0.0;

        $daily = new Collection();
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->startOfDay();
            $count = AdClick::where('ad_id', $ad->id)
                ->whereBetween('created_at', [$day, $day->copy()->endOfDay()])
                ->count();
            $daily->push(['label' => $day->isoFormat('DD/MM'), 'count' => $count]);
        }

        $maxDaily = max(1, (int) $daily->max('count'));

        return view('admin.ads.report', compact('ad', 'today', 'last7', 'last30', 'total', 'ctr', 'daily', 'maxDaily'));
    }

    /**
     * @return array<string, mixed>
     */
    private function data(AdRequest $request): array
    {
        $validated = $request->validated();

        return [
            'title' => $validated['title'],
            'image_url' => $validated['image_url'],
            'link_url' => $validated['link_url'],
            'placement' => $validated['placement'],
            'active' => $request->boolean('active'),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ];
    }
}
