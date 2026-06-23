<x-auth-layout title="Esqueci a senha">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Esqueci a senha</h1>
        <p class="mt-1 text-sm text-slate-500">Enviaremos um link para você criar uma nova senha</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email" placeholder="voce@exemplo.com"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2.5 font-medium text-white transition hover:bg-slate-700 disabled:opacity-60">
            Enviar link de redefinição
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-400">
        <a href="{{ route('login') }}" class="hover:text-slate-600">← Voltar ao login</a>
    </p>
</x-auth-layout>
