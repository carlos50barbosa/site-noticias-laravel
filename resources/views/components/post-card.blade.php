@props(['post', 'size' => 'md'])

@php
    $cover = $post->coverImage();
    $titleCls = match ($size) {
        'lg' => 'text-2xl sm:text-3xl',
        'sm' => 'text-base',
        default => 'text-lg',
    };
@endphp

<article {{ $attributes->merge(['class' => 'group flex flex-col']) }}>
    <a href="{{ route('noticia', $post->slug) }}" class="flex flex-col gap-3">
        <div class="relative aspect-[16/9] overflow-hidden rounded-xl bg-slate-100">
            @if ($cover)
                <img src="{{ $cover }}" alt="{{ $post->title }}" loading="lazy"
                     class="absolute inset-0 h-full w-full object-cover transition duration-300 group-hover:scale-105">
            @else
                <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 text-sm text-slate-400">
                    Sem imagem
                </div>
            @endif
        </div>

        <div>
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
        </div>
    </a>
</article>
