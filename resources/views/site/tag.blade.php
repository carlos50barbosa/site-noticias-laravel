@extends('layouts.public')

@section('title', '#'.$tag->name)
@section('meta_description', 'Notícias com a tag '.$tag->name.'.')

@push('head')
    <link rel="canonical" href="{{ route('tag', $tag->slug) }}">
@endpush

@section('content')
    <header class="mb-6 border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900">#{{ $tag->name }}</h1>
    </header>

    @if ($posts->isEmpty())
        <p class="rounded-md bg-white p-8 text-center text-slate-500 ring-1 ring-slate-200">
            Nenhuma notícia com esta tag ainda.
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
