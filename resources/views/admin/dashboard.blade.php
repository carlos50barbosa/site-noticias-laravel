<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
            <span class="font-bold text-slate-900">{{ config('app.name') }} · Painel</span>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-slate-600">
                    {{ auth()->user()->name }}
                    <span class="ml-1 rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ auth()->user()->role->label() }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sky-700 hover:underline">Sair</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-10">
        <h1 class="text-2xl font-bold text-slate-900">Bem-vindo ao painel</h1>
        <p class="mt-2 text-slate-600">
            Autenticação e papéis ativos. As telas de gestão (notícias, categorias, usuários,
            comentários, publicidade, configurações) chegam nas próximas fases.
        </p>
    </main>
</body>
</html>
