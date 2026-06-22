@extends('layouts.admin')

@section('title', 'Usuários')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-bold text-slate-900">Novo usuário</h2>
                <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Nome</label>
                        <input id="name" name="name" value="{{ old('name') }}" required
                               class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Senha</label>
                        <input id="password" type="password" name="password" required
                               class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700">Papel</label>
                        <select id="role" name="role" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}" @selected(old('role') === $role->value)>{{ $role->label() }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Criar</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">Papel</th>
                            <th class="px-4 py-3">Notícias</th>
                            <th class="px-4 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">
                                        {{ $user->name }}
                                        @if ($user->is(auth()->user()))<span class="text-xs text-slate-400">(você)</span>@endif
                                    </div>
                                    <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->role->label() }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->posts_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.usuarios.edit', $user) }}" class="text-sky-700 hover:underline">Editar</a>
                                    <form method="POST" action="{{ route('admin.usuarios.destroy', $user) }}" class="ml-3 inline"
                                          onsubmit="return confirm('Excluir este usuário?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" @disabled($user->is(auth()->user()) || $user->posts_count > 0)
                                                class="text-red-600 hover:underline disabled:cursor-not-allowed disabled:text-slate-300">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
