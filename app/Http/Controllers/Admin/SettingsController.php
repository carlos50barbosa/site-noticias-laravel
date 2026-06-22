<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\SiteSetting;
use App\Support\Audit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', ['settings' => SiteSetting::current()]);
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        // Confirmação de senha do admin antes de salvar.
        if (! Hash::check((string) $request->input('current_password'), $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Senha incorreta.'])->withInput();
        }

        SiteSetting::current()->update([
            'site_name' => $request->validated('site_name'),
            'logo_url' => $request->validated('logo_url'),
            'favicon_url' => $request->validated('favicon_url'),
            'adsense_client' => $request->validated('adsense_client'),
        ]);

        Audit::log('settings.update', 'settings');

        return back()->with('success', 'Configurações salvas.');
    }
}
