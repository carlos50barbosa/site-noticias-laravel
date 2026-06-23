@extends('layouts.admin')

@section('title', 'Estatísticas')

@php($card = 'rounded-lg bg-white p-5 ring-1 ring-slate-200')

@section('content')
    <h1 class="mb-4 text-2xl font-bold text-slate-900">Estatísticas</h1>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="{{ $card }}">
            <div class="text-xs uppercase text-slate-400">Acessos ao site</div>
            <div class="mt-1 text-3xl font-bold">{{ number_format($visits, 0, ',', '.') }}</div>
        </div>
        <div class="{{ $card }}">
            <div class="text-xs uppercase text-slate-400">Leituras de notícias</div>
            <div class="mt-1 text-3xl font-bold">{{ number_format($totalReads, 0, ',', '.') }}</div>
        </div>
        <div class="{{ $card }}">
            <div class="text-xs uppercase text-slate-400">Notícias publicadas</div>
            <div class="mt-1 text-3xl font-bold">{{ $published }} <span class="text-base font-normal text-slate-400">/ {{ $totalPosts }}</span></div>
        </div>
        <div class="{{ $card }}">
            <div class="text-xs uppercase text-slate-400">Comentários</div>
            <div class="mt-1 text-3xl font-bold">{{ $commentsTotal }} <span class="text-base font-normal text-amber-500">({{ $commentsPending }} pendentes)</span></div>
        </div>
    </div>

    <div class="mt-6 rounded-lg bg-white p-5 ring-1 ring-slate-200">
        <h2 class="mb-3 text-sm font-semibold text-slate-700">Mais lidas</h2>
        @if ($topPosts->isEmpty())
            <p class="text-sm text-slate-400">Sem dados ainda.</p>
        @else
            <ol class="space-y-2">
                @foreach ($topPosts as $post)
                    <li class="flex items-center justify-between text-sm">
                        <a href="{{ route('noticia', $post->slug) }}" target="_blank" class="text-slate-800 hover:underline">{{ $post->title }}</a>
                        <span class="text-slate-500">{{ number_format($post->view_count, 0, ',', '.') }} leituras</span>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
@endsection
