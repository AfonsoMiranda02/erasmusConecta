@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Bem-vindo de volta, {{ Auth::user()->nome }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Atividades em Curso -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Atividades em curso</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['atividades_em_curso'] }}</p>
                </div>
            </div>
        </div>

        <!-- Candidaturas Submetidas -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Candidaturas submetidas</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['candidaturas_submetidas'] }}</p>
                </div>
            </div>
        </div>

        <!-- Candidaturas Aprovadas -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Candidaturas aprovadas</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['candidaturas_aprovadas'] }}</p>
                </div>
            </div>
        </div>

        <!-- Mensagens por Ler -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Mensagens por ler</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['mensagens_nao_lidas'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acessos Rápidos -->
    <div class="bg-white border border-gray-200 rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Acessos Rápidos</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-blue-50 transition-colors group">
                    <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5 group-hover:bg-teal-50 transition-colors">
                        <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800 group-hover:text-teal-700">Nova candidatura</p>
                        <p class="text-xs text-gray-500 mt-0.5">Submeter nova candidatura</p>
                    </div>
                </a>

                <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-blue-50 transition-colors group">
                    <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5 group-hover:bg-teal-50 transition-colors">
                        <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800 group-hover:text-teal-700">Ver atividades</p>
                        <p class="text-xs text-gray-500 mt-0.5">Explorar atividades disponíveis</p>
                    </div>
                </a>

                <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-blue-50 transition-colors group">
                    <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5 group-hover:bg-teal-50 transition-colors">
                        <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800 group-hover:text-teal-700">Instituições parceiras</p>
                        <p class="text-xs text-gray-500 mt-0.5">Consultar instituições</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Candidaturas Recentes -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-800">Candidaturas Recentes</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($inscricoes_recentes as $inscricao)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $inscricao->evento->titulo ?? 'Evento não encontrado' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Submetida em {{ $inscricao->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                @if($inscricao->estado === 'aprovada')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                        Aprovada
                                    </span>
                                @elseif($inscricao->estado === 'pendente')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        Pendente
                                    </span>
                                @elseif($inscricao->estado === 'rejeitada')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                        Rejeitada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                        {{ ucfirst($inscricao->estado) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-gray-500">
                        Ainda não tens candidaturas submetidas.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Convites e Notificações -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-800">Atividades Recentes</h2>
            </div>
            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse($convites_recentes->take(3) as $convite)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $convite->titulo }}</p>
                                @if($convite->descricao)
                                <p class="text-xs text-gray-600 mt-1 italic">{{ $convite->descricao }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">
                                    De {{ $convite->remetente->nome ?? 'Utilizador' }} • {{ $convite->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if($convite->estado === 'pendente')
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    Novo
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                @endforelse

                @forelse($notificacoes_recentes->take(3) as $notificacao)
                    <div class="p-4 hover:bg-gray-50 transition-colors {{ !$notificacao->is_seen ? 'bg-blue-50' : '' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $notificacao->titulo }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notificacao->mensagem }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notificacao->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notificacao->is_seen)
                                <span class="ml-2 h-2 w-2 bg-teal-400 rounded-full"></span>
                            @endif
                        </div>
                    </div>
                @empty
                    @if($convites_recentes->isEmpty())
                        <div class="p-4 text-center text-sm text-gray-500">
                            Não há atividades recentes.
                        </div>
                    @endif
                @endforelse
            </div>
        </div>
    </div>

    <!-- Eventos Próximos -->
    @if($eventos_proximos->isNotEmpty())
    <div class="bg-white border border-gray-200 rounded-lg mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Eventos Próximos</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($eventos_proximos as $evento)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $evento->titulo }}</p>
                            <div class="mt-1 flex items-center text-xs text-gray-500 space-x-4">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $evento->data_hora->format('d/m/Y H:i') }}
                                </span>
                                @if($evento->local)
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $evento->local }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="#" class="ml-4 text-sm text-teal-600 hover:text-teal-700 font-medium">
                            Ver detalhes
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

