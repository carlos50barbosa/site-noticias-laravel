@extends('layouts.admin')

@section('title', 'Relatório do anúncio')

@php
    $card = 'rounded-lg bg-white p-4 ring-1 ring-slate-200';
@endphp

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">{{ $ad->title }}</h1>
        <a href="{{ route('admin.publicidades.index') }}" class="text-sm text-slate-500 hover:underline">← Voltar</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <div class="{{ $card }}"><div class="text-xs uppercase text-slate-400">Hoje</div><div class="mt-1 text-2xl font-bold">{{ $today }}</div></div>
        <div class="{{ $card }}"><div class="text-xs uppercase text-slate-400">7 dias</div><div class="mt-1 text-2xl font-bold">{{ $last7 }}</div></div>
        <div class="{{ $card }}"><div class="text-xs uppercase text-slate-400">30 dias</div><div class="mt-1 text-2xl font-bold">{{ $last30 }}</div></div>
        <div class="{{ $card }}"><div class="text-xs uppercase text-slate-400">Total cliques</div><div class="mt-1 text-2xl font-bold">{{ $total }}</div></div>
        <div class="{{ $card }}"><div class="text-xs uppercase text-slate-400">Impressões</div><div class="mt-1 text-2xl font-bold">{{ $ad->impressions }}</div></div>
        <div class="{{ $card }}"><div class="text-xs uppercase text-slate-400">CTR</div><div class="mt-1 text-2xl font-bold">{{ $ctr }}%</div></div>
    </div>

    <div class="mt-6 rounded-lg bg-white p-5 ring-1 ring-slate-200">
        <h2 class="mb-4 text-sm font-semibold text-slate-700">Cliques nos últimos 7 dias</h2>
        <div class="flex items-end gap-3" style="height: 160px;">
            @foreach ($daily as $day)
                <div class="flex flex-1 flex-col items-center justify-end">
                    <span class="mb-1 text-xs text-slate-500">{{ $day['count'] }}</span>
                    <div class="w-full rounded-t bg-sky-500"
                         style="height: {{ (int) round($day['count'] / $maxDaily * 130) }}px; min-height: 2px;"></div>
                    <span class="mt-1 text-xs text-slate-400">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endsection
