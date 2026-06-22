<x-auth-layout title="Esqueci a senha">
    <h1 class="mb-2 text-lg font-semibold text-slate-800">Esqueci a senha</h1>
    <p class="mb-4 text-sm text-slate-500">Informe seu e-mail e enviaremos um link para redefinir a senha.</p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800">
            Enviar link
        </button>

        <p class="text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="text-sky-700 hover:underline">Voltar ao login</a>
        </p>
    </form>
</x-auth-layout>
