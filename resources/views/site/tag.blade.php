@extends('layouts.public')

@section('title', '#'.$tag->name)
@section('meta_description', 'Notícias com a tag '.$tag->name.'.')

@push('head')
    <link rel="canonical" href="{{ route('tag', $tag->slug) }}">
@endpush

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-10">
        <header class="mb-8 border-b border-slate-200 pb-4">
            <p class="text-sm font-semibold uppercase tracking-wide text-sky-700">Tag</p>
            <h1 class="text-3xl font-extrabold text-slate-900">#{{ $tag->name }}</h1>
        </header>

        @if ($posts->isEmpty())
            <p class="py-16 text-center text-slate-500">Nenhuma notícia publicada com esta tag.</p>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
            {{ $posts->links('partials.pagination') }}
        @endif
    </div>
@endsection
