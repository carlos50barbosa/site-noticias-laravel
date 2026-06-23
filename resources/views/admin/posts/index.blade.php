@extends('layouts.admin')

@section('title', 'Notícias')

@php
    $badge = [
        'DRAFT' => 'bg-slate-100 text-slate-600',
        'PENDING' => 'bg-amber-100 text-amber-700',
        'PUBLISHED' => 'bg-emerald-100 text-emerald-700',
        'SCHEDULED' => 'bg-sky-100 text-sky-700',
    ];
@endphp

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Notícias</h1>
        <a href="{{ route('admin.noticias.create') }}" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Nova notícia</a>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <select name="status" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm">
            <option value="">Todos os status</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected($filterStatus === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        @if ($authors->isNotEmpty())
            <select name="author" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm">
                <option value="">Todos os autores</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" @selected($filterAuthor === $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
        @endif
        <button type="submit" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm hover:bg-slate-50">Filtrar</button>
    </form>

    <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-slate-200">
        <table class="w-full min-w-[640px] text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Título</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Categoria</th>
                    <th class="px-4 py-3">Autor</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($posts as $post)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">{{ $post->title }}</div>
                            <div class="text-xs text-slate-400">{{ $post->updated_at?->isoFormat('DD/MM/YYYY HH:mm') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $badge[$post->status->value] }}">{{ $post->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $post->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $post->author?->name }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.noticias.edit', $post) }}" class="text-sky-700 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.noticias.destroy', $post) }}" class="ml-3 inline"
                                  onsubmit="return confirm('Excluir esta notícia?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">Nenhuma notícia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $posts->links() }}</div>
@endsection
