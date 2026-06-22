@props(['placement'])

@php($ad = \App\Support\Ads::pick(\App\Enums\AdPlacement::from($placement)))

@if ($ad)
    <div {{ $attributes->merge(['class' => 'text-center']) }}>
        <a href="{{ route('ads.click', $ad) }}" target="_blank" rel="nofollow sponsored noopener">
            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="mx-auto max-w-full rounded">
        </a>
    </div>
@endif
