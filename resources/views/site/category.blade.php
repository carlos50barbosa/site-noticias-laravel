@extends('layouts.public')

@section('title', $category->name)
@section('meta_description', $category->description ?: ('Notícias de '.$category->name.'.'))

@push('head')
    <link rel="canonical" href="{{ route('categoria', $category->slug) }}">
@endpush

@section('content')
    <header class="mb-6 border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="mt-1 text-slate-600">{{ $category->description }}</p>
        @endif
    </header>

    @if ($posts->isEmpty())
        <p class="rounded-md bg-white p-8 text-center text-slate-500 ring-1 ring-slate-200">
            Nenhuma notícia nesta categoria ainda.
        </p>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>

        <div class="mt-8">{{ $posts->links() }}</div>
    @endif
@endsection
