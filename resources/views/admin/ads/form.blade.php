@extends('layouts.admin')

@php($editing = $ad->exists)

@section('title', $editing ? 'Editar anúncio' : 'Novo anúncio')

@section('content')
    <div class="max-w-2xl">
        <form method="POST"
              action="{{ $editing ? route('admin.publicidades.update', $ad) : route('admin.publicidades.store') }}"
              class="space-y-5 rounded-lg bg-white p-5 ring-1 ring-slate-200">
            @csrf
            @if ($editing) @method('PUT') @endif

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Nome (uso interno)</label>
                <input id="title" name="title" value="{{ old('title', $ad->title) }}" required
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div data-image-field>
                <span class="block text-sm font-medium text-slate-700">Banner (imagem)</span>
                <input type="hidden" data-url name="image_url" value="{{ old('image_url', $ad->image_url) }}">
                <img data-preview src="{{ $ad->image_url }}" alt="Banner"
                     class="mt-2 max-h-32 rounded-md {{ $ad->image_url ? '' : 'hidden' }}">
                <input type="file" data-file accept="image/*" class="mt-2 w-full text-sm">
                @error('image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="link_url" class="block text-sm font-medium text-slate-700">Link de destino</label>
                <input id="link_url" type="url" name="link_url" value="{{ old('link_url', $ad->link_url) }}" required placeholder="https://..."
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                @error('link_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="placement" class="block text-sm font-medium text-slate-700">Posição</label>
                <select id="placement" name="placement" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    @foreach (\App\Enums\AdPlacement::cases() as $placement)
                        <option value="{{ $placement->value }}" @selected(old('placement', $ad->placement?->value) === $placement->value)>{{ $placement->label() }}</option>
                    @endforeach
                </select>
                @error('placement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-slate-700">Início (opcional)</label>
                    <input id="starts_at" type="datetime-local" name="starts_at"
                           value="{{ old('starts_at', $ad->starts_at?->format('Y-m-d\TH:i')) }}"
                           class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-slate-700">Fim (opcional)</label>
                    <input id="ends_at" type="datetime-local" name="ends_at"
                           value="{{ old('ends_at', $ad->ends_at?->format('Y-m-d\TH:i')) }}"
                           class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    @error('ends_at')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="active" value="1" @checked(old('active', $ad->active)) class="rounded border-slate-300">
                Ativo
            </label>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Salvar</button>
                <a href="{{ route('admin.publicidades.index') }}" class="text-sm text-slate-500 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
