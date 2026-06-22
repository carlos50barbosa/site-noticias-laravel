@extends('layouts.public')

@php($cover = $post->coverImage())
@php($coverAbs = $cover ? url($cover) : null)
@php($canonical = route('noticia', $post->slug))
@php($description = $post->excerptText(200))

@section('title', $post->title)
@section('meta_description', $description)

@push('head')
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="{{ $settings->site_name }}">
    @if ($coverAbs)
        <meta property="og:image" content="{{ $coverAbs }}">
    @endif
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    @if ($post->author)
        <meta property="article:author" content="{{ $post->author->name }}">
    @endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ $coverAbs ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ $description }}">
    @if ($coverAbs)
        <meta name="twitter:image" content="{{ $coverAbs }}">
    @endif

    {{-- JSON-LD NewsArticle --}}
    @php($jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $post->title,
        'description' => $description,
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'mainEntityOfPage' => $canonical,
        'author' => ['@type' => 'Person', 'name' => $post->author?->name],
        'publisher' => ['@type' => 'Organization', 'name' => $settings->site_name],
    ] + ($coverAbs ? ['image' => [$coverAbs]] : []))
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <div class="grid gap-8 lg:grid-cols-3">
        <article class="lg:col-span-2">
            @if ($post->category)
                <a href="{{ route('categoria', $post->category->slug) }}"
                   class="text-sm font-semibold uppercase tracking-wide text-sky-700 hover:underline">{{ $post->category->name }}</a>
            @endif

            <h1 class="mt-1 text-3xl font-extrabold leading-tight text-slate-900">{{ $post->title }}</h1>

            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                @if ($post->author)
                    Por <a href="{{ route('autor', $post->author->id) }}" class="font-medium text-slate-700 hover:underline">{{ $post->author->name }}</a> ·
                @endif
                <time datetime="{{ $post->published_at?->toIso8601String() }}">
                    {{ $post->published_at?->isoFormat('D [de] MMMM [de] YYYY') }}
                </time>
            </div>

            @if ($post->excerpt)
                <p class="mt-4 text-lg text-slate-600">{{ $post->excerpt }}</p>
            @endif

            @if ($cover)
                <img src="{{ $cover }}" alt="{{ $post->title }}"
                     class="mt-6 aspect-video w-full rounded-lg object-cover">
            @endif

            <div class="article-content mt-6">
                {!! $post->content !!}
            </div>

            <x-ad placement="ARTICLE" class="my-8" />

            @if ($post->tags->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('tag', $tag->slug) }}"
                           class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700 hover:bg-slate-200">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Comentários --}}
            <section class="mt-10 border-t border-slate-200 pt-6">
                <h2 class="mb-4 text-lg font-bold text-slate-900">Comentários ({{ $comments->count() }})</h2>

                @if (session('comment_status'))
                    <div class="mb-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('comment_status') }}</div>
                @endif
                @if (session('comment_error'))
                    <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('comment_error') }}</div>
                @endif

                <form method="POST" action="{{ route('comentarios.store', $post) }}" class="mb-6 space-y-3">
                    @csrf
                    <div>
                        <input name="author_name" value="{{ old('author_name') }}" placeholder="Seu nome" required
                               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        @error('author_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <textarea name="content" rows="3" placeholder="Escreva um comentário..." required
                                  class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">{{ old('content') }}</textarea>
                        @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Enviar comentário</button>
                </form>

                <div class="space-y-4">
                    @forelse ($comments as $comment)
                        <div class="border-b border-slate-100 pb-3">
                            <div class="text-sm font-semibold text-slate-800">{{ $comment->author_name }}</div>
                            <div class="text-xs text-slate-400">{{ $comment->created_at?->isoFormat('D [de] MMM [de] YYYY, HH:mm') }}</div>
                            <p class="mt-1 text-sm text-slate-700">{{ $comment->content }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Seja o primeiro a comentar.</p>
                    @endforelse
                </div>
            </section>

            <div class="mt-10">
                <a href="{{ route('home') }}" class="text-sm text-sky-700 hover:underline">← Voltar à home</a>
            </div>
        </article>

        <aside class="lg:col-span-1">
            @if ($related->isNotEmpty())
                <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200">
                    <h2 class="mb-3 text-lg font-bold text-slate-900">Leia também</h2>
                    <ul class="space-y-4">
                        @foreach ($related as $item)
                            <li>
                                <a href="{{ route('noticia', $item->slug) }}" class="font-medium text-slate-800 hover:underline">{{ $item->title }}</a>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->published_at?->isoFormat('D [de] MMM [de] YYYY') }}</p>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </aside>
    </div>
@endsection
