<x-auth-layout title="Redefinir senha">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Redefinir senha</h1>
        <p class="mt-1 text-sm text-slate-500">Crie uma nova senha para sua conta</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required readonly
                   class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-slate-600 outline-none">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Nova senha</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Confirmar nova senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 outline-none transition focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2.5 font-medium text-white transition hover:bg-slate-700 disabled:opacity-60">
            Redefinir senha
        </button>
    </form>
</x-auth-layout>
