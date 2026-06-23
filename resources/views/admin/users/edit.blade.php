@extends('layouts.admin')

@section('title', 'Editar usuário')

@section('content')
    <div class="max-w-xl">
        <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
            <h2 class="mb-4 text-lg font-bold text-slate-900">Editar usuário</h2>
            <form method="POST" action="{{ route('admin.usuarios.update', $user) }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nome</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Nova senha <span class="text-slate-400">(deixe em branco para manter)</span></label>
                    <input id="password" type="password" name="password"
                           class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700">Papel</label>
                    <select id="role" name="role" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        @foreach ($roles as $role)
                            <option value="{{ $role->value }}" @selected(old('role', $user->role->value) === $role->value)>{{ $role->label() }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Salvar</button>
                    <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-slate-500 hover:underline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
