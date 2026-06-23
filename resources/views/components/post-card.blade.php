@props(['post', 'size' => 'md'])

@php
    $titleCls = match ($size) {
        'lg' => 'text-2xl sm:text-3xl',
        'sm' => 'text-base',
        default => 'text-lg',
    };
@endphp

<article {{ $attributes->merge(['class' => 'group flex flex-col']) }}>
    <x-post-media :post="$post" />

    <a href="{{ route('noticia', $post->slug) }}" class="mt-3 block">
        @if ($post->category)
            <span class="text-xs font-semibold uppercase tracking-wide text-sky-700">{{ $post->category->name }}</span>
        @endif
        <h3 class="mt-1 font-bold leading-snug text-slate-900 transition group-hover:text-sky-800 {{ $titleCls }}">
            {{ $post->title }}
        </h3>
        @if ($size !== 'sm')
            <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $post->excerptText() }}</p>
        @endif
        <p class="mt-2 text-xs text-slate-400">
            {{ $post->author?->name ?? 'Redação' }}@if ($post->published_at) · {{ $post->published_at->isoFormat('DD [de] MMMM [de] YYYY') }}@endif
        </p>
    </a>
</article>
