@extends('layouts.admin')

@section('title', 'Detalhes da Atividade')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <a 
                href="{{ route('admin.atividades.index') }}" 
                class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
            >
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar às atividades
            </a>
            <h1 class="text-2xl font-semibold text-gray-800 mt-2">{{ $evento->titulo }}</h1>
            <p class="mt-1 text-sm text-gray-500">Detalhes da atividade</p>
        </div>
        <div class="flex items-center space-x-2">
            <a 
                href="{{ route('admin.atividades.edit', $evento->id) }}" 
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                Editar
            </a>
        </div>
    </div>

    <!-- Informações da Atividade -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Informações</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Título</p>
                <p class="mt-1 text-sm text-gray-900">{{ $evento->titulo }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tipo</p>
                <p class="mt-1 text-sm text-gray-900">{{ $evento->tipo->nome ?? 'N/A' }}</p>
            </div>
            @if($evento->descricao)
            <div class="md:col-span-2">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Descrição</p>
                <p class="mt-1 text-sm text-gray-900">{{ $evento->descricao }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Data e Hora</p>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $evento->data_hora ? $evento->data_hora->format('d/m/Y H:i') : 'N/A' }}
                </p>
            </div>
            @if($evento->local)
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Local</p>
                <p class="mt-1 text-sm text-gray-900">{{ $evento->local }}</p>
            </div>
            @endif
            @if($evento->capacidade)
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Capacidade</p>
                <p class="mt-1 text-sm text-gray-900">{{ $evento->capacidade }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</p>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        @if($evento->status === 'aprovado') bg-green-50 text-green-700 border border-green-200
                        @elseif($evento->status === 'pendente') bg-yellow-50 text-yellow-700 border border-yellow-200
                        @else bg-red-50 text-red-700 border border-red-200
                        @endif">
                        {{ ucfirst($evento->status) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Visibilidade</p>
                <p class="mt-1">
                    @if($evento->is_public)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                            Pública
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                            Por Convite
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Criador</p>
                <p class="mt-1 text-sm text-gray-900">{{ $evento->criador->nome ?? 'N/A' }}</p>
            </div>
            @if($evento->aprovador)
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Aprovado por</p>
                <p class="mt-1 text-sm text-gray-900">{{ $evento->aprovador->nome ?? 'N/A' }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Inscrições -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Inscrições</h2>
        @if($evento->inscricoes->isEmpty())
            <p class="text-sm text-gray-500">Não há inscrições para esta atividade.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilizador</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($evento->inscricoes as $inscricao)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inscricao->user->nome ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($inscricao->estado === 'aprovada') bg-green-50 text-green-700 border border-green-200
                                    @elseif($inscricao->estado === 'pendente') bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @else bg-red-50 text-red-700 border border-red-200
                                    @endif">
                                    {{ ucfirst($inscricao->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $inscricao->created_at ? $inscricao->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Convites -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Convites</h2>
        @if($evento->convites->isEmpty())
            <p class="text-sm text-gray-500">Não há convites para esta atividade.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Intercambista</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enviado por</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($evento->convites as $convite)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $convite->destinatario->nome ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $convite->remetente->nome ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($convite->estado === 'aceite') bg-green-50 text-green-700 border border-green-200
                                    @elseif($convite->estado === 'pendente') bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @else bg-red-50 text-red-700 border border-red-200
                                    @endif">
                                    {{ ucfirst($convite->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $convite->created_at ? $convite->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

