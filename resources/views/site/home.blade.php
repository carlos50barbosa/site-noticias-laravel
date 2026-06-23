@extends('layouts.public')

@section('title', $settings->site_name)
@section('meta_description', 'Últimas notícias e reportagens de '.$settings->site_name.'.')

@section('content')
    @if ($latest->isEmpty())
        <div class="mx-auto max-w-6xl px-4 py-24 text-center">
            <h1 class="text-2xl font-bold text-slate-900">Ainda não há notícias publicadas</h1>
            <p class="mt-2 text-slate-500">
                Volte em breve ou acesse o
                <a href="{{ url('/admin') }}" class="text-sky-700 underline">painel</a>
                para publicar.
            </p>
        </div>
    @else
        @php
            $hero = $latest->first();
            $secondary = $latest->slice(1, 2);
            $recent = $latest->slice(3);
            $heroCover = $hero->coverImage();
        @endphp

        <div class="mx-auto max-w-6xl px-4 py-8">
            @if ($pinned->isNotEmpty())
                <section class="mb-10 rounded-xl border border-yellow-100/20 bg-yellow-50/[0.07] p-4">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-label="Fixados" role="img" class="mb-3 h-4 w-4 text-slate-300">
                        <path d="M15.113 3.21l.094.083 5.5 5.5a1 1 0 0 1-1.175 1.59l-3.172 3.171-1.424 3.797a1 1 0 0 1-.228.357l-1.5 1.5a1 1 0 0 1-1.32.083l-.095-.083-2.793-2.792-3.793 3.792a1 1 0 0 1-1.497-1.32l.083-.094 3.792-3.793-2.792-2.793a1 1 0 0 1-.083-1.32l.083-.094 1.5-1.5a1 1 0 0 1 .357-.228l3.796-1.425 3.171-3.17a1 1 0 0 1 1.499.076z" />
                    </svg>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($pinned as $post)
                            <x-post-card :post="$post" size="sm" />
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Destaque principal --}}
            <section class="grid gap-8 lg:grid-cols-3">
                <article class="group lg:col-span-2">
                    <a href="{{ route('noticia', $hero->slug) }}" class="block">
                        <div class="relative aspect-[16/9] overflow-hidden rounded-2xl bg-slate-100">
                            @if ($heroCover)
                                <img src="{{ $heroCover }}" alt="{{ $hero->title }}"
                                     class="absolute inset-0 h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 text-slate-400">
                                    Sem imagem
                                </div>
                            @endif
                        </div>
                        @if ($hero->category)
                            <span class="mt-3 inline-block text-xs font-semibold uppercase tracking-wide text-sky-700">{{ $hero->category->name }}</span>
                        @endif
                        <h1 class="mt-1 text-3xl font-extrabold leading-tight text-slate-900 transition group-hover:text-sky-800 sm:text-4xl">{{ $hero->title }}</h1>
                        <p class="mt-3 text-lg text-slate-600">{{ $hero->excerptText(200) }}</p>
                        <p class="mt-2 text-sm text-slate-400">
                            {{ $hero->author?->name ?? 'Redação' }}@if ($hero->published_at) · {{ $hero->published_at->isoFormat('DD [de] MMMM [de] YYYY') }}@endif
                        </p>
                    </a>
                </article>

                <div class="flex flex-col gap-6">
                    @foreach ($secondary as $post)
                        <x-post-card :post="$post" size="sm" />
                    @endforeach
                </div>
            </section>

            <x-ad placement="HOME" class="my-10" />

            {{-- Mais recentes --}}
            @if ($recent->isNotEmpty())
                <section class="mt-12">
                    <h2 class="mb-6 border-l-4 border-sky-700 pl-3 text-xl font-bold text-slate-900">Mais recentes</h2>
                    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($recent as $post)
                            <x-post-card :post="$post" />
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Mais lidas --}}
            @if ($mostRead->isNotEmpty())
                <section class="mt-12">
                    <h2 class="mb-6 border-l-4 border-amber-500 pl-3 text-xl font-bold text-slate-900">Mais lidas</h2>
                    <ol class="grid gap-2 sm:grid-cols-2">
                        @foreach ($mostRead as $i => $post)
                            <li>
                                <a href="{{ route('noticia', $post->slug) }}"
                                   class="flex items-start gap-3 rounded-lg p-2 transition hover:bg-slate-50">
                                    <span class="text-2xl font-extrabold leading-none text-slate-300">{{ $i + 1 }}</span>
                                    <span class="font-medium text-slate-800">{{ $post->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif
        </div>
    @endif
@endsection
