<!DOCTYPE html>
<html lang="pt-BR" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') — {{ config('app.name') }}</title>
    {{ Vite::fonts() }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900" x-data="{ open: false }">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-30 flex w-60 -translate-x-full flex-col justify-between bg-slate-900 p-4 text-white transition lg:static lg:translate-x-0"
               :class="open && 'translate-x-0'">
            <div>
                <a href="{{ url('/admin') }}" class="mb-6 block px-3 text-lg font-bold text-white">Painel</a>
                <nav class="flex flex-col gap-1">
                    <x-admin.nav-link :href="url('/admin')" :active="request()->is('admin') || request()->is('admin/noticias*')">Notícias</x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.noticias.create')" :active="request()->is('admin/noticias/nova')">Nova notícia</x-admin.nav-link>
                    @can('publish-posts')
                        <x-admin.nav-link :href="route('admin.revisao')" :active="request()->is('admin/revisao*')" :badge="$pendingReviewCount ?: null">Revisão</x-admin.nav-link>
                    @endcan
                    @can('manage-all-posts')
                        <x-admin.nav-link :href="route('admin.comentarios.index')" :active="request()->is('admin/comentarios*')" :badge="$pendingCommentsCount ?: null">Comentários</x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.estatisticas')" :active="request()->is('admin/estatisticas*')">Estatísticas</x-admin.nav-link>
                    @endcan
                    @can('manage-categories')
                        <x-admin.nav-link :href="route('admin.categorias.index')" :active="request()->is('admin/categorias*')">Categorias</x-admin.nav-link>
                    @endcan
                    @can('manage-users')
                        <x-admin.nav-link :href="route('admin.usuarios.index')" :active="request()->is('admin/usuarios*')">Usuários</x-admin.nav-link>
                    @endcan
                    @can('manage-ads')
                        <x-admin.nav-link :href="route('admin.publicidades.index')" :active="request()->is('admin/publicidades*')">Publicidades</x-admin.nav-link>
                    @endcan
                    @can('manage-settings')
                        <x-admin.nav-link :href="route('admin.configuracoes.edit')" :active="request()->is('admin/configuracoes*')">Configurações</x-admin.nav-link>
                    @endcan
                    @can('view-audit-logs')
                        <x-admin.nav-link :href="route('admin.logs')" :active="request()->is('admin/logs*')">Logs</x-admin.nav-link>
                    @endcan
                </nav>
            </div>

            <div class="border-t border-slate-700 pt-4">
                <div class="px-3 pb-2">
                    <p class="truncate text-sm font-medium">{{ auth()->user()->name ?? auth()->user()->email }}</p>
                    <p class="text-xs text-slate-400">{{ auth()->user()->role->label() }}</p>
                </div>
                <a href="{{ route('home') }}" target="_blank"
                   class="block rounded-lg px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white">Ver site</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full rounded-lg px-3 py-2 text-left text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white disabled:opacity-60">Sair</button>
                </form>
            </div>
        </aside>

        {{-- Overlay no mobile --}}
        <div x-show="open" @click="open = false" x-cloak class="fixed inset-0 z-20 bg-black/40 lg:hidden"></div>

        <div class="flex min-h-screen flex-1 flex-col">
            {{-- Barra superior só no mobile (abre a sidebar) --}}
            <header class="flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-3 lg:hidden">
                <button @click="open = true" class="text-slate-600" aria-label="Menu">☰</button>
                <span class="text-sm font-semibold text-slate-700">@yield('title', 'Painel')</span>
            </header>

            <main class="flex-1 overflow-x-hidden p-4 sm:p-8">
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
