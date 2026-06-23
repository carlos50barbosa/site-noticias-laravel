@extends('layouts.admin')

@php($editing = $post->exists)

@section('title', $editing ? 'Editar notícia' : 'Nova notícia')

@section('content')
    @if ($post->review_note)
        <div class="mb-4 rounded-md border-l-4 border-amber-400 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <strong>Devolvido para revisão:</strong> {{ $post->review_note }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
            Corrija os campos destacados abaixo.
        </div>
    @endif

    <form method="POST"
          action="{{ $editing ? route('admin.noticias.update', $post) : route('admin.noticias.store') }}"
          class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        {{-- Coluna principal --}}
        <div class="space-y-5 lg:col-span-2">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Título</label>
                <input id="title" name="title" value="{{ old('title', $post->title) }}" required
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Conteúdo</label>
                @php($btn = 'rounded px-2 py-1 text-sm font-medium text-slate-700 hover:bg-slate-200')
                <div data-editor class="mt-1 rounded-md border border-slate-300 bg-white">
                    <div class="flex flex-wrap gap-1 border-b border-slate-200 bg-slate-50 p-2">
                        <button type="button" data-cmd="bold" class="{{ $btn }} font-bold">N</button>
                        <button type="button" data-cmd="italic" class="{{ $btn }} italic">I</button>
                        <button type="button" data-cmd="h2" class="{{ $btn }}">T2</button>
                        <button type="button" data-cmd="h3" class="{{ $btn }}">T3</button>
                        <button type="button" data-cmd="bulletList" class="{{ $btn }}">• Lista</button>
                        <button type="button" data-cmd="orderedList" class="{{ $btn }}">1. Lista</button>
                        <button type="button" data-cmd="blockquote" class="{{ $btn }}">❝ Citação</button>
                        <button type="button" data-cmd="link" class="{{ $btn }}">🔗 Link</button>
                        <button type="button" data-cmd="image" class="{{ $btn }}">🖼 Imagem</button>
                        <button type="button" data-cmd="youtube" class="{{ $btn }}">▶ YouTube</button>
                        <span class="mx-1 w-px bg-slate-200"></span>
                        <button type="button" data-cmd="undo" class="{{ $btn }}">↶</button>
                        <button type="button" data-cmd="redo" class="{{ $btn }}">↷</button>
                    </div>
                    <div data-editor-content></div>
                    <textarea data-editor-input name="content" class="hidden">{{ old('content', $post->content) }}</textarea>
                    <input type="file" data-image-input accept="image/*" class="hidden">
                </div>
                @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-slate-700">Resumo (opcional)</label>
                <textarea id="excerpt" name="excerpt" rows="2"
                          class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Sidebar de configurações --}}
        <div class="space-y-5 lg:col-span-1">
            <div class="rounded-lg bg-white p-4 ring-1 ring-slate-200"
                 x-data="{ status: '{{ old('status', $post->status?->value ?? 'DRAFT') }}' }">
                <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                <select id="status" name="status" x-model="status"
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="DRAFT">Rascunho</option>
                    <option value="PENDING">Pendente (enviar para revisão)</option>
                    @can('publish-posts')
                        <option value="PUBLISHED">Publicado</option>
                        <option value="SCHEDULED">Agendado</option>
                    @endcan
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror

                <div x-show="status === 'SCHEDULED'" x-cloak class="mt-3">
                    <label for="scheduled_at" class="block text-sm font-medium text-slate-700">Publicar em</label>
                    <input id="scheduled_at" type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at', $post->status === \App\Enums\PostStatus::SCHEDULED ? $post->published_at?->format('Y-m-d\TH:i') : '') }}"
                           class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    @error('scheduled_at')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @can('publish-posts')
                    <label class="mt-3 flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="pinned" value="1" @checked(old('pinned', $post->pinned)) class="rounded border-slate-300">
                        Fixar na home
                    </label>
                @endcan
            </div>

            <div class="rounded-lg bg-white p-4 ring-1 ring-slate-200">
                <label for="category_id" class="block text-sm font-medium text-slate-700">Categoria</label>
                <select id="category_id" name="category_id" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">— Sem categoria —</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>

                <label for="tags" class="mt-4 block text-sm font-medium text-slate-700">Tags (separadas por vírgula)</label>
                <input id="tags" name="tags" value="{{ old('tags', $post->tags->pluck('name')->implode(', ')) }}"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                @error('tags')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="rounded-lg bg-white p-4 ring-1 ring-slate-200" data-image-field>
                <span class="block text-sm font-medium text-slate-700">Imagem de capa</span>
                <input type="hidden" data-url name="cover_image_url" value="{{ old('cover_image_url', $post->cover_image_url) }}">
                <img data-preview src="{{ $post->cover_image_url }}" alt="Capa"
                     class="mt-2 aspect-video w-full rounded-md object-cover {{ $post->cover_image_url ? '' : 'hidden' }}">
                <input type="file" data-file accept="image/*" class="mt-2 w-full text-sm">
                @error('cover_image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Salvar</button>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:underline">Cancelar</a>
            </div>
        </div>
    </form>
@endsection
