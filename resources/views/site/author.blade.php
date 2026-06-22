@extends('layouts.public')

@section('title', $author->name)
@section('meta_description', 'Notícias publicadas por '.$author->name.'.')

@push('head')
    <link rel="canonical" href="{{ route('autor', $author->id) }}">
@endpush

@section('content')
    <header class="mb-6 border-b border-slate-200 pb-4">
        <p class="text-sm uppercase tracking-wide text-slate-500">Autor</p>
        <h1 class="text-2xl font-extrabold text-slate-900">{{ $author->name }}</h1>
    </header>

    @if ($posts->isEmpty())
        <p class="rounded-md bg-white p-8 text-center text-slate-500 ring-1 ring-slate-200">
            Este autor ainda não tem notícias publicadas.
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
