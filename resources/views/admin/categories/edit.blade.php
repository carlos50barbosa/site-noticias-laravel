@extends('layouts.admin')

@section('title', 'Editar categoria')

@section('content')
    <div class="max-w-xl">
        <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
            <h2 class="mb-4 text-lg font-bold text-slate-900">Editar categoria</h2>
            <form method="POST" action="{{ route('admin.categorias.update', $category) }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nome</label>
                    <input id="name" name="name" value="{{ old('name', $category->name) }}" required
                           class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700">Descrição</label>
                    <textarea id="description" name="description" rows="3"
                              class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">{{ old('description', $category->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Salvar</button>
                    <a href="{{ route('admin.categorias.index') }}" class="text-sm text-slate-500 hover:underline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
