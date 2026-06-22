@props(['post'])

@php($cover = $post->coverImage())

<article {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-lg bg-white ring-1 ring-slate-200']) }}>
    @if ($cover)
        <a href="{{ route('noticia', $post->slug) }}" class="block aspect-video overflow-hidden bg-slate-100">
            <img src="{{ $cover }}" alt="{{ $post->title }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        </a>
    @endif

    <div class="flex flex-1 flex-col p-4">
        @if ($post->category)
            <a href="{{ route('categoria', $post->category->slug) }}"
               class="text-xs font-semibold uppercase tracking-wide text-sky-700 hover:underline">
                {{ $post->category->name }}
            </a>
        @endif

        <h3 class="mt-1 font-bold leading-snug text-slate-900">
            <a href="{{ route('noticia', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
        </h3>

        <p class="mt-2 text-sm text-slate-600">{{ $post->excerptText() }}</p>

        <div class="mt-3 text-xs text-slate-500">
            @if ($post->author)
                <a href="{{ route('autor', $post->author->id) }}" class="hover:underline">{{ $post->author->name }}</a>
                ·
            @endif
            {{ $post->published_at?->isoFormat('D [de] MMMM [de] YYYY') }}
        </div>
    </div>
</article>
