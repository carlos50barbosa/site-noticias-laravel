@extends('layouts.admin')

@section('title', 'Logs de auditoria')

@php
    $labels = [
        'post.create' => 'Criou notícia', 'post.update' => 'Editou notícia', 'post.delete' => 'Excluiu notícia',
        'post.publish' => 'Publicou notícia', 'post.return' => 'Devolveu notícia',
        'user.create' => 'Criou usuário', 'user.update' => 'Editou usuário', 'user.delete' => 'Excluiu usuário',
        'category.create' => 'Criou categoria', 'category.update' => 'Editou categoria', 'category.delete' => 'Excluiu categoria',
        'ad.create' => 'Criou anúncio', 'ad.update' => 'Editou anúncio', 'ad.delete' => 'Excluiu anúncio',
        'settings.update' => 'Atualizou configurações',
    ];
@endphp

@section('content')
    <h1 class="mb-4 text-2xl font-bold text-slate-900">Logs de auditoria</h1>

    <div class="overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Data</th>
                    <th class="px-4 py-3">Usuário</th>
                    <th class="px-4 py-3">Ação</th>
                    <th class="px-4 py-3">Entidade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-slate-500">{{ $log->created_at?->isoFormat('DD/MM/YYYY HH:mm') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->user_email ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-800">{{ $labels[$log->action] ?? $log->action }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $log->entity }}{{ $log->entity_id ? ' #'.$log->entity_id : '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Nenhum registro.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
