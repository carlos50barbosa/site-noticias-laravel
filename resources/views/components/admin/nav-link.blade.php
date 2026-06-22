@props(['href', 'active' => false])

<a href="{{ $href }}"
   {{ $attributes->class([
        'block rounded px-3 py-2 text-sm font-medium transition',
        'bg-slate-800 text-white' => $active,
        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $active,
   ]) }}>
    {{ $slot }}
</a>
