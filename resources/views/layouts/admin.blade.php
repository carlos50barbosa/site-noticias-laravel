<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased" x-data="{ open: false }">
    <div class="lg:flex">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-30 w-60 -translate-x-full bg-slate-900 p-4 transition lg:static lg:translate-x-0"
               :class="open && 'translate-x-0'">
            <a href="{{ url('/admin') }}" class="mb-6 block px-3 text-lg font-bold text-white">{{ config('app.name') }}</a>
            <nav class="space-y-1">
                <x-admin.nav-link :href="url('/admin')" :active="request()->is('admin') || request()->is('admin/noticias*')">Notícias</x-admin.nav-link>
                @can('publish-posts')
                    <x-admin.nav-link :href="route('admin.revisao')" :active="request()->is('admin/revisao*')">Revisão</x-admin.nav-link>
                @endcan
                @can('manage-all-posts')
                    <x-admin.nav-link :href="route('admin.comentarios.index')" :active="request()->is('admin/comentarios*')">Comentários</x-admin.nav-link>
                @endcan
                @can('manage-categories')
                    <x-admin.nav-link :href="route('admin.categorias.index')" :active="request()->is('admin/categorias*')">Categorias</x-admin.nav-link>
                @endcan
                @can('manage-ads')
                    <x-admin.nav-link :href="route('admin.publicidades.index')" :active="request()->is('admin/publicidades*')">Publicidades</x-admin.nav-link>
                @endcan
                @can('manage-all-posts')
                    <x-admin.nav-link :href="route('admin.estatisticas')" :active="request()->is('admin/estatisticas*')">Estatísticas</x-admin.nav-link>
                @endcan
                @can('manage-users')
                    <x-admin.nav-link :href="route('admin.usuarios.index')" :active="request()->is('admin/usuarios*')">Usuários</x-admin.nav-link>
                @endcan
                @can('view-audit-logs')
                    <x-admin.nav-link :href="route('admin.logs')" :active="request()->is('admin/logs*')">Logs</x-admin.nav-link>
                @endcan
                @can('manage-settings')
                    <x-admin.nav-link :href="route('admin.configuracoes.edit')" :active="request()->is('admin/configuracoes*')">Configurações</x-admin.nav-link>
                @endcan
            </nav>
        </aside>

        {{-- Overlay no mobile --}}
        <div x-show="open" @click="open = false" x-cloak class="fixed inset-0 z-20 bg-black/40 lg:hidden"></div>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3">
                <button @click="open = true" class="text-slate-600 lg:hidden" aria-label="Menu">☰</button>
                <span class="text-sm font-semibold text-slate-700">@yield('title', 'Painel')</span>
                <div class="flex items-center gap-3 text-sm">
                    <a href="{{ route('home') }}" target="_blank" class="text-slate-500 hover:underline">Ver site</a>
                    <span class="text-slate-600">
                        {{ auth()->user()->name }}
                        <span class="ml-1 rounded bg-slate-100 px-2 py-0.5 text-xs">{{ auth()->user()->role->label() }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sky-700 hover:underline">Sair</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                @if (session('success'))
                    <div class="mb-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
