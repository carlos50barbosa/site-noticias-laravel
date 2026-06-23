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
                    <div class="mt-2 rounded-md bg-slate-800 p-3">
                        <img data-preview src="{{ $settings->logo_url }}" alt="Logo" class="max-h-12 {{ $settings->logo_url ? '' : 'hidden' }}">
                    </div>
                    <input type="file" data-file accept="image/*" class="mt-2 w-full text-sm">
                </div>

                <div data-image-field>
                    <span class="block text-sm font-medium text-slate-700">Ícone (favicon)</span>
                    <img data-preview src="{{ $settings->favicon_url }}" alt="Favicon" class="mt-2 h-12 w-12 rounded {{ $settings->favicon_url ? '' : 'hidden' }}">
                    <input type="hidden" data-url name="favicon_url" value="{{ old('favicon_url', $settings->favicon_url) }}">
                    <input type="file" data-file accept="image/*" class="mt-2 w-full text-sm">
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
