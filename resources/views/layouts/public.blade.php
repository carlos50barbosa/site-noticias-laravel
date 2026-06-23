<!DOCTYPE html>
<html lang="pt-BR" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $settings->site_name)</title>
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif
    @if ($settings->favicon_url)
        <link rel="icon" href="{{ $settings->favicon_url }}?v={{ $settings->updated_at?->timestamp }}">
    @endif
    <link rel="alternate" type="application/rss+xml" title="{{ $settings->site_name }}" href="{{ url('/feed.xml') }}">
    @stack('head')
    @if (app()->isProduction() && $settings->adsense_client)
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $settings->adsense_client }}" crossorigin="anonymous"></script>
    @endif
    {{ Vite::fonts() }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white text-slate-900">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="{{ $settings->site_name }}">
                @if ($settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_name }}" class="h-9 w-auto object-contain">
                @endif
                <span class="text-xl font-extrabold tracking-tight text-slate-900">{{ $settings->site_name }}</span>
            </a>

            <div class="flex items-center gap-3">
                <form action="{{ route('busca') }}" method="GET" class="hidden sm:block sm:w-56">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar notícias…"
                           aria-label="Buscar notícias"
                           class="w-full rounded-full border border-slate-300 px-4 py-1.5 text-sm outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
                </form>

                @auth
                    <div class="flex items-center gap-1">
                        <a href="{{ url('/admin') }}"
                           class="rounded-lg px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Painel</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-lg px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 disabled:opacity-60">Sair</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        <nav class="border-t border-slate-100">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-5 gap-y-1 px-4 py-2 text-sm font-medium text-slate-600">
                <a href="{{ route('home') }}" class="hover:text-sky-700">Início</a>
                @foreach ($navCategories as $category)
                    <a href="{{ route('categoria', $category->slug) }}" class="hover:text-sky-700">{{ $category->name }}</a>
                @endforeach
            </div>
        </nav>
    </header>

    <x-ad placement="SITEWIDE" class="mx-auto max-w-6xl px-4 pt-4" />

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-slate-200 bg-slate-50">
        <div class="mx-auto max-w-6xl px-4 py-8 text-sm text-slate-500">
            <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
                <p>© {{ now()->year }} {{ $settings->site_name }}. Todos os direitos reservados.</p>
                <nav class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
                    <a href="{{ route('privacidade') }}" class="hover:text-slate-800">Política de Privacidade</a>
                    <a href="{{ route('termos') }}" class="hover:text-slate-800">Termos de Uso</a>
                    <a href="{{ url('/admin') }}" class="hover:text-slate-800">Painel</a>
                </nav>
            </div>
            <p class="mt-4 text-center text-xs text-slate-400 sm:text-right">
                Desenvolvido por:
                <a href="https://servicostech.com.br/" target="_blank" rel="noopener"
                   class="font-medium text-slate-500 transition hover:text-slate-800 hover:underline">Serviços Tech</a>
            </p>
        </div>
    </footer>
</body>
</html>
