@extends('layouts.admin')

@section('title', 'Comentários')

@php
    $badge = [
        'PENDING' => 'bg-amber-100 text-amber-700',
        'APPROVED' => 'bg-emerald-100 text-emerald-700',
        'REJECTED' => 'bg-red-100 text-red-700',
    ];
@endphp

@section('content')
    <h1 class="mb-4 text-2xl font-bold text-slate-900">Comentários</h1>

    <div class="overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Autor / Comentário</th>
                    <th class="px-4 py-3">Notícia</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($comments as $comment)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">{{ $comment->author_name }}</div>
                            <div class="text-slate-600">{{ \Illuminate\Support\Str::limit($comment->content, 120) }}</div>
                            <div class="text-xs text-slate-400">{{ $comment->created_at?->isoFormat('DD/MM/YYYY HH:mm') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($comment->post)
                                <a href="{{ route('noticia', $comment->post->slug) }}" target="_blank" class="text-sky-700 hover:underline">{{ \Illuminate\Support\Str::limit($comment->post->title, 40) }}</a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $badge[$comment->status->value] }}">{{ $comment->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            @if ($comment->status !== \App\Enums\CommentStatus::APPROVED)
                                <form method="POST" action="{{ route('admin.comentarios.approve', $comment) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-700 hover:underline">Aprovar</button>
                                </form>
                            @endif
                            @if ($comment->status !== \App\Enums\CommentStatus::REJECTED)
                                <form method="POST" action="{{ route('admin.comentarios.reject', $comment) }}" class="ml-2 inline">
                                    @csrf
                                    <button type="submit" class="text-amber-700 hover:underline">Rejeitar</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.comentarios.destroy', $comment) }}" class="ml-2 inline"
                                  onsubmit="return confirm('Excluir este comentário?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Nenhum comentário.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
