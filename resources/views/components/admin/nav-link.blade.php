@props(['href', 'active' => false, 'badge' => null])

<a href="{{ $href }}"
   {{ $attributes->class([
        'flex items-center justify-between rounded-lg px-3 py-2 text-sm transition',
        'bg-slate-800 font-medium text-white' => $active,
        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $active,
   ]) }}>
    <span>{{ $slot }}</span>
    @if ($badge)
        <span class="rounded-full bg-amber-500 px-2 py-0.5 text-xs font-semibold text-white">{{ $badge }}</span>
    @endif
</a>
