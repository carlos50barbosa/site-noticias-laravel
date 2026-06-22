@extends('layouts.public')

@section('title', $settings->site_name)
@section('meta_description', 'Últimas notícias e reportagens de '.$settings->site_name.'.')

@section('content')
    @if ($pinned->isNotEmpty())
        <div class="mb-8 space-y-2">
            @foreach ($pinned as $post)
                <a href="{{ route('noticia', $post->slug) }}"
                   class="flex items-center gap-3 rounded-md border-l-4 border-amber-400 bg-amber-50 px-4 py-3 hover:bg-amber-100">
                    <span class="text-amber-500">📌</span>
                    <span class="font-semibold text-slate-800">{{ $post->title }}</span>
                </a>
            @endforeach
        </div>
    @endif

    <x-ad placement="HOME" class="mb-8" />

    @if ($latest->isEmpty())
        <p class="rounded-md bg-white p-8 text-center text-slate-500 ring-1 ring-slate-200">
            Nenhuma notícia publicada ainda.
        </p>
    @else
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                @php($hero = $latest->first())
                @php($secondary = $latest->slice(1, 2))
                @php($recent = $latest->slice(3))

                {{-- Manchete --}}
                <article class="group overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
                    @php($cover = $hero->coverImage())
                    @if ($cover)
                        <a href="{{ route('noticia', $hero->slug) }}" class="block aspect-video overflow-hidden bg-slate-100">
                            <img src="{{ $cover }}" alt="{{ $hero->title }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        </a>
                    @endif
                    <div class="p-6">
                        @if ($hero->category)
                            <a href="{{ route('categoria', $hero->category->slug) }}"
                               class="text-xs font-semibold uppercase tracking-wide text-sky-700 hover:underline">{{ $hero->category->name }}</a>
                        @endif
                        <h1 class="mt-1 text-2xl font-extrabold leading-tight text-slate-900">
                            <a href="{{ route('noticia', $hero->slug) }}" class="hover:underline">{{ $hero->title }}</a>
                        </h1>
                        <p class="mt-2 text-slate-600">{{ $hero->excerptText(220) }}</p>
                    </div>
                </article>

                @if ($secondary->isNotEmpty())
                    <div class="grid gap-6 sm:grid-cols-2">
                        @foreach ($secondary as $post)
                            <x-post-card :post="$post" />
                        @endforeach
                    </div>
                @endif

                @if ($recent->isNotEmpty())
                    <section>
                        <h2 class="mb-4 border-b border-slate-200 pb-2 text-lg font-bold text-slate-900">Mais recentes</h2>
                        <div class="grid gap-6 sm:grid-cols-2">
                            @foreach ($recent as $post)
                                <x-post-card :post="$post" />
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="lg:col-span-1">
                <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200">
                    <h2 class="mb-3 text-lg font-bold text-slate-900">Mais lidas</h2>
                    @if ($mostRead->isEmpty())
                        <p class="text-sm text-slate-500">Sem dados ainda.</p>
                    @else
                        <ol class="space-y-3">
                            @foreach ($mostRead as $i => $post)
                                <li class="flex gap-3">
                                    <span class="text-xl font-extrabold text-slate-300">{{ $i + 1 }}</span>
                                    <a href="{{ route('noticia', $post->slug) }}"
                                       class="text-sm font-medium text-slate-800 hover:underline">{{ $post->title }}</a>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>
            </aside>
        </div>
    @endif
@endsection
