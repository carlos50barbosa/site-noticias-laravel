@extends('layouts.admin')

@section('title', 'Configurações')

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.configuracoes.update') }}"
              class="space-y-5 rounded-lg bg-white p-5 ring-1 ring-slate-200">
            @csrf @method('PUT')

            <div>
                <label for="site_name" class="block text-sm font-medium text-slate-700">Nome do site</label>
                <input id="site_name" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                @error('site_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div data-image-field>
                    <span class="block text-sm font-medium text-slate-700">Logo</span>
                    <input type="hidden" data-url name="logo_url" value="{{ old('logo_url', $settings->logo_url) }}">
                    <label class="group mt-2 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-5 text-center transition hover:border-sky-400 hover:bg-sky-50">
                        <img data-preview src="{{ $settings->logo_url }}" alt="Logo" class="max-h-16 w-auto object-contain {{ $settings->logo_url ? '' : 'hidden' }}">
                        <span class="text-sm text-slate-500">
                            <svg class="mx-auto mb-1 h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 16V4m0 0L8 8m4-4l4 4"/><path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/>
                            </svg>
                            <span class="font-medium text-sky-700">Clique para enviar</span> ou trocar a logo
                            <span class="mt-0.5 block text-xs text-slate-400">PNG, JPG ou SVG</span>
                        </span>
                        <input type="file" data-file accept="image/*" class="hidden">
                    </label>
                </div>

                <div data-image-field>
                    <span class="block text-sm font-medium text-slate-700">Ícone (favicon)</span>
                    <input type="hidden" data-url name="favicon_url" value="{{ old('favicon_url', $settings->favicon_url) }}">
                    <label class="group mt-2 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-5 text-center transition hover:border-sky-400 hover:bg-sky-50">
                        <img data-preview src="{{ $settings->favicon_url }}" alt="Favicon" class="h-12 w-12 rounded object-contain {{ $settings->favicon_url ? '' : 'hidden' }}">
                        <span class="text-sm text-slate-500">
                            <svg class="mx-auto mb-1 h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 16V4m0 0L8 8m4-4l4 4"/><path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/>
                            </svg>
                            <span class="font-medium text-sky-700">Clique para enviar</span> ou trocar o ícone
                            <span class="mt-0.5 block text-xs text-slate-400">PNG ou ICO (quadrado)</span>
                        </span>
                        <input type="file" data-file accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            <div>
                <label for="adsense_client" class="block text-sm font-medium text-slate-700">Google AdSense (ID do publisher)</label>
                <input id="adsense_client" name="adsense_client" value="{{ old('adsense_client', $settings->adsense_client) }}" placeholder="ca-pub-XXXXXXXXXXXXXXXX"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                @error('adsense_client')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-slate-400">Os anúncios só aparecem em produção, com conta aprovada.</p>
            </div>

            <div class="border-t border-slate-200 pt-4">
                <label for="current_password" class="block text-sm font-medium text-slate-700">Confirme sua senha para salvar</label>
                <input id="current_password" type="password" name="current_password" required
                       class="mt-1 w-full max-w-xs rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                @error('current_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Salvar configurações</button>
        </form>
    </div>
@endsection
