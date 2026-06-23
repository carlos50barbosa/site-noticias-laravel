@extends('layouts.admin')

@section('title', 'Revisão')

@section('content')
    <h1 class="mb-4 text-2xl font-bold text-slate-900">Fila de revisão</h1>

    @if ($posts->isEmpty())
        <p class="rounded-md bg-white p-8 text-center text-slate-400 ring-1 ring-slate-200">Nenhuma notícia aguardando revisão.</p>
    @else
        <div class="space-y-4">
            @foreach ($posts as $post)
                <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200" x-data="{ returning: false }">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="font-bold text-slate-900">{{ $post->title }}</h2>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $post->author?->name }} · {{ $post->category?->name ?? 'Sem categoria' }} ·
                                enviado em {{ $post->updated_at?->isoFormat('DD/MM/YYYY HH:mm') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.noticias.edit', $post) }}" class="text-sm text-slate-500 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.revisao.approve', $post) }}">
                                @csrf
                                <button type="submit" class="rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-emerald-700">Publicar</button>
                            </form>
                            <button type="button" @click="returning = !returning"
                                    class="rounded-md border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-50">Devolver</button>
                        </div>
                    </div>

                    <form x-show="returning" x-cloak method="POST" action="{{ route('admin.revisao.return', $post) }}" class="mt-3">
                        @csrf
                        <textarea name="note" rows="2" required placeholder="Comentário para o autor..."
                                  class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"></textarea>
                        <button type="submit" class="mt-2 rounded-md bg-amber-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-amber-700">Devolver com comentário</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
@endsection
