@extends('layouts.public')

@section('title', 'Busca')

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    <header class="mb-6">
        <h1 class="mb-3 text-2xl font-extrabold text-slate-900">Busca</h1>
        <form action="{{ route('busca') }}" method="GET" class="flex gap-2">
            <input type="search" name="q" value="{{ $query }}" placeholder="Buscar notícias..." autofocus
                   class="w-full max-w-md rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Buscar</button>
        </form>
    </header>

    @if (is_null($posts))
        <p class="text-slate-500">Digite ao menos 2 caracteres para buscar.</p>
    @elseif ($posts->isEmpty())
        <p class="rounded-md bg-white p-8 text-center text-slate-500 ring-1 ring-slate-200">
            Nenhum resultado para “{{ $query }}”.
        </p>
    @else
        <p class="mb-4 text-sm text-slate-500">{{ $posts->total() }} resultado(s) para “{{ $query }}”.</p>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>
        <div class="mt-8">{{ $posts->links() }}</div>
    @endif
@endsection
