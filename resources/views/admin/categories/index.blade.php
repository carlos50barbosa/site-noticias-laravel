@extends('layouts.admin')

@section('title', 'Categorias')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-bold text-slate-900">Nova categoria</h2>
                <form method="POST" action="{{ route('admin.categorias.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Nome</label>
                        <input id="name" name="name" value="{{ old('name') }}" required
                               class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Descrição</label>
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Criar</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-slate-200">
                <table class="w-full min-w-[480px] text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">Notícias</th>
                            <th class="px-4 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $category->name }}</div>
                                    <div class="text-xs text-slate-400">/{{ $category->slug }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $category->posts_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.categorias.edit', $category) }}" class="text-sky-700 hover:underline">Editar</a>
                                    <form method="POST" action="{{ route('admin.categorias.destroy', $category) }}" class="ml-3 inline"
                                          onsubmit="return confirm('Excluir esta categoria?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" @disabled($category->posts_count > 0)
                                                class="text-red-600 hover:underline disabled:cursor-not-allowed disabled:text-slate-300">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-slate-400">Nenhuma categoria.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
