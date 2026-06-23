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
    <div class="mx-auto max-w-3xl px-4 py-10">
        <article>
            @if ($post->category)
                <a href="{{ route('categoria', $post->category->slug) }}"
                   class="text-sm font-semibold uppercase tracking-wide text-sky-700 hover:underline">{{ $post->category->name }}</a>
            @endif

            <h1 class="mt-2 text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">{{ $post->title }}</h1>

            @if ($post->excerpt)
                <p class="mt-4 text-xl leading-relaxed text-slate-600">{{ $post->excerpt }}</p>
            @endif

            <div class="mt-4 flex items-center gap-2 text-sm text-slate-500">
                <span>
                    Por
                    @if ($post->author)
                        <a href="{{ route('autor', $post->author->id) }}" class="font-medium text-slate-700 hover:underline">{{ $post->author->name }}</a>
                    @else
                        <span class="font-medium text-slate-700">Redação</span>
                    @endif
                </span>
                @if ($post->published_at)
                    <span aria-hidden="true">·</span>
                    <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->isoFormat('DD [de] MMMM [de] YYYY') }}</time>
                @endif
            </div>

            @if ($cover)
                <div class="relative mt-6 aspect-[16/9] overflow-hidden rounded-2xl bg-slate-100">
                    <img src="{{ $cover }}" alt="{{ $post->title }}" class="absolute inset-0 h-full w-full object-cover">
                </div>
            @endif

            <div class="article-content mt-8">
                {!! $post->content !!}
            </div>

            @if ($post->tags->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('tag', $tag->slug) }}"
                           class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-600 transition hover:bg-slate-200">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif
        </article>

        <x-ad placement="ARTICLE" class="my-8" />

        {{-- Leia também --}}
        @if ($related->isNotEmpty())
            <section class="mt-12 border-t border-slate-200 pt-8">
                <h2 class="mb-6 text-xl font-bold text-slate-900">Leia também</h2>
                <div class="grid gap-8 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <x-post-card :post="$item" size="sm" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Comentários --}}
        <section class="mt-12 border-t border-slate-200 pt-8">
            <h2 class="mb-6 text-xl font-bold text-slate-900">Comentários ({{ $comments->count() }})</h2>

            <div class="mb-8 space-y-4">
                @forelse ($comments as $comment)
                    <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-900">{{ $comment->author_name }}</span>
                            <span class="text-xs text-slate-400">{{ $comment->created_at?->isoFormat('DD [de] MMMM [de] YYYY') }}</span>
                        </div>
                        <p class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $comment->content }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Seja o primeiro a comentar.</p>
                @endforelse
            </div>

            <h3 class="mb-3 font-semibold text-slate-900">Deixe seu comentário</h3>

            @if (session('comment_status'))
                <p class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('comment_status') }}</p>
            @else
                <form method="POST" action="{{ route('comentarios.store', $post) }}" class="space-y-3">
                    @csrf
                    <div>
                        <input name="author_name" value="{{ old('author_name') }}" placeholder="Seu nome" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">
                        @error('author_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <textarea name="content" rows="3" placeholder="Escreva seu comentário…" required
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10">{{ old('content') }}</textarea>
                        @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    @if (session('comment_error'))
                        <p class="text-sm text-red-600">{{ session('comment_error') }}</p>
                    @endif
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700 disabled:opacity-60">Comentar</button>
                </form>
            @endif
        </section>

        <div class="mt-10 border-t border-slate-200 pt-6">
            <a href="{{ route('home') }}" class="text-sm text-sky-700 hover:underline">← Voltar para a home</a>
        </div>
    </div>
@endsection
