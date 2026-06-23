@extends('layouts.admin')

@section('title', 'Publicidades')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Publicidades</h1>
        <a href="{{ route('admin.publicidades.create') }}" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Novo anúncio</a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Anúncio</th>
                    <th class="px-4 py-3">Posição</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Impr.</th>
                    <th class="px-4 py-3">Cliques</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($ads as $ad)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $ad->image_url }}" alt="" class="h-10 w-16 rounded object-cover">
                                <span class="font-medium text-slate-900">{{ $ad->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $ad->placement->label() }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $ad->active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $ad->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $ad->impressions }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $ad->clicks }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.publicidades.report', $ad) }}" class="text-slate-600 hover:underline">Relatório</a>
                            <a href="{{ route('admin.publicidades.edit', $ad) }}" class="ml-2 text-sky-700 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.publicidades.destroy', $ad) }}" class="ml-2 inline"
                                  onsubmit="return confirm('Excluir este anúncio?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">Nenhum anúncio.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
