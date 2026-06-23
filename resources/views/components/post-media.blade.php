@props(['post', 'rounded' => 'rounded-xl'])

@php
    $videoId = $post->videoId();
    $poster = $post->coverImage(); // foto explícita ou, na falta, miniatura do YouTube
    $href = route('noticia', $post->slug);
@endphp

<div class="relative aspect-[16/9] overflow-hidden bg-slate-100 {{ $rounded }}">
    @if ($videoId)
        {{-- Notícia com vídeo: toca direto na home (carrega o iframe só ao clicar). --}}
        <div x-data="{ playing: false }" class="absolute inset-0">
            <button type="button" x-show="!playing" @click="playing = true"
                    class="group/play absolute inset-0 h-full w-full cursor-pointer"
                    aria-label="Reproduzir vídeo">
                @if ($poster)
                    <img src="{{ $poster }}" alt="{{ $post->title }}" loading="lazy"
                         class="absolute inset-0 h-full w-full object-cover">
                @endif
                <span class="absolute inset-0 flex items-center justify-center bg-black/20 transition group-hover/play:bg-black/30">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-red-600/90 shadow-lg transition group-hover/play:scale-110">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="ml-1 h-7 w-7 text-white" aria-hidden="true">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </span>
                </span>
            </button>
            <template x-if="playing">
                <iframe src="{{ \App\Support\Youtube::embedUrl($videoId, true) }}"
                        title="{{ $post->title }}"
                        class="absolute inset-0 h-full w-full"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen></iframe>
            </template>
        </div>
    @elseif ($poster)
        <a href="{{ $href }}" class="block h-full w-full">
            <img src="{{ $poster }}" alt="{{ $post->title }}" loading="lazy"
                 class="absolute inset-0 h-full w-full object-cover transition duration-300 group-hover:scale-105">
        </a>
    @else
        <a href="{{ $href }}"
           class="flex h-full items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 text-sm text-slate-400">
            Sem imagem
        </a>
    @endif
</div>
