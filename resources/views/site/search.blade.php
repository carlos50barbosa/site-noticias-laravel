@extends('layouts.public')

@section('title', $query !== '' ? 'Busca: '.$query : 'Busca')

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-10">
        <form action="{{ route('busca') }}" method="GET" class="mb-8">
            <input type="search" name="q" value="{{ $query }}" placeholder="Buscar notícias…" autofocus
                   aria-label="Buscar notícias"
                   class="w-full rounded-full border border-slate-300 px-5 py-3 text-lg outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
        </form>

        @if (is_null($posts))
            <p class="text-slate-500">Digite ao menos 2 caracteres para buscar.</p>
        @else
            <h1 class="mb-6 text-xl font-bold text-slate-900">
                {{ $posts->total() }} resultado{{ $posts->total() === 1 ? '' : 's' }} para
                <span class="text-sky-700">“{{ $query }}”</span>
            </h1>

            @if ($posts->isEmpty())
                <p class="text-slate-500">Nenhuma notícia encontrada. Tente outros termos.</p>
            @else
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
                {{ $posts->links('partials.pagination') }}
            @endif
        @endif
    </div>
@endsection
