@extends('layouts.public')

@section('title', $category->name)
@section('meta_description', $category->description ?: ('Notícias de '.$category->name.'.'))

@push('head')
    <link rel="canonical" href="{{ route('categoria', $category->slug) }}">
@endpush

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-10">
        <header class="mb-8 border-b border-slate-200 pb-4">
            <h1 class="text-3xl font-extrabold text-slate-900">{{ $category->name }}</h1>
            @if ($category->description)
                <p class="mt-2 text-slate-600">{{ $category->description }}</p>
            @endif
        </header>

        @if ($posts->isEmpty())
            <p class="py-16 text-center text-slate-500">Nenhuma notícia publicada nesta categoria ainda.</p>
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
