<x-auth-layout title="Entrar">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Painel</h1>
        <p class="mt-1 text-sm text-slate-500">Entre com suas credenciais</p>
    </div>

    <form method="POST" action="{{ url('/admin/login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email" placeholder="voce@exemplo.com"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Senha</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2.5 font-medium text-white transition hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-60">
            Entrar
        </button>
    </form>

    <div class="mt-6 flex items-center justify-between text-sm text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-slate-600">← Voltar ao site</a>
        <a href="{{ route('password.request') }}" class="hover:text-slate-600">Esqueci a senha</a>
    </div>
</x-auth-layout>
