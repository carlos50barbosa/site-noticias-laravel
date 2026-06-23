@if ($paginator->hasPages())
    @php
        $base = 'rounded-lg border px-4 py-2 text-sm font-medium transition';
        $enabled = 'border-slate-300 text-slate-700 hover:bg-slate-100';
        $disabled = 'pointer-events-none border-slate-200 text-slate-300';
    @endphp
    <nav class="mt-10 flex items-center justify-center gap-3">
        @if ($paginator->onFirstPage())
            <span class="{{ $base }} {{ $disabled }}">← Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $base }} {{ $enabled }}">← Anterior</a>
        @endif

        <span class="text-sm text-slate-500">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $base }} {{ $enabled }}">Próxima →</a>
        @else
            <span class="{{ $base }} {{ $disabled }}">Próxima →</span>
        @endif
    </nav>
@endif
