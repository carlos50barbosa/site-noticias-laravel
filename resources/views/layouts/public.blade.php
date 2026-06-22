<!DOCTYPE html>
<html lang="pt-BR">
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
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                @if ($settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_name }}" class="h-8 w-auto">
                @else
                    <span class="text-xl font-extrabold tracking-tight text-slate-900">{{ $settings->site_name }}</span>
                @endif
            </a>

            <div class="flex items-center gap-4">
                <form action="{{ route('busca') }}" method="GET" class="hidden sm:block">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar..."
                           class="w-48 rounded-md border border-slate-300 px-3 py-1.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                </form>
                @auth
                    <a href="{{ url('/admin') }}" class="text-sm font-medium text-sky-700 hover:underline">Painel</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-sky-700 hover:underline">Entrar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-6 text-sm text-slate-500">
            © {{ now()->year }} {{ $settings->site_name }} ·
            <a href="{{ url('/feed.xml') }}" class="hover:underline">RSS</a>
        </div>
    </footer>
</body>
</html>
