@extends('layouts.app')

@section('title', 'Detalhes da Mensagem')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('mensagens.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-teal-600">
                    Mensagens
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detalhes</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Notification Details Card -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">{{ $notificacao->titulo }}</h1>
                        <p class="text-sm text-gray-500">Recebida em {{ $notificacao->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if(!$notificacao->is_seen)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                        Nova
                    </span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-6 space-y-6">
            <!-- Message -->
            <div>
                <h2 class="text-sm font-medium text-gray-700 mb-2">Mensagem</h2>
                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $notificacao->mensagem }}</p>
            </div>

            <!-- Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                <!-- Data de Criação -->
                <div>
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Data de Criação</h3>
                    <p class="text-sm text-gray-900">{{ $notificacao->created_at->format('d/m/Y H:i:s') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notificacao->created_at->diffForHumans() }}</p>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Status</h3>
                    <p class="text-sm text-gray-900">
                        @if($notificacao->is_seen)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Lida
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Não lida
                            </span>
                        @endif
                    </p>
                </div>

                <!-- Destinatário -->
                <div>
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Destinatário</h3>
                    <p class="text-sm text-gray-900">{{ $notificacao->user->nome }}</p>
                    <p class="text-xs text-gray-500">{{ $notificacao->user->email }}</p>
                </div>

                <!-- Última Atualização -->
                <div>
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Última Atualização</h3>
                    <p class="text-sm text-gray-900">{{ $notificacao->updated_at->format('d/m/Y H:i:s') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notificacao->updated_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Related Information -->
            @if($morphableInfo)
                <div class="pt-6 border-t border-gray-200">
                    <h2 class="text-sm font-medium text-gray-700 mb-4">Informações Relacionadas</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                        <!-- Tipo -->
                        <div>
                            <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tipo</h3>
                            <p class="text-sm font-medium text-gray-900">{{ $morphableInfo['tipo'] }}</p>
                        </div>

                        @if($morphableInfo['tipo'] === 'Atividade')
                            <!-- Atividade Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Título da Atividade</h3>
                                    <p class="text-sm text-gray-900">{{ $morphableInfo['titulo'] }}</p>
                                </div>
                                
                                @if(isset($morphableInfo['tipo_atividade']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tipo de Atividade</h3>
                                        <p class="text-sm text-gray-900">{{ $morphableInfo['tipo_atividade']->nome ?? 'N/A' }}</p>
                                    </div>
                                @endif

                                @if(isset($morphableInfo['data_hora']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Data e Hora</h3>
                                        <p class="text-sm text-gray-900">{{ $morphableInfo['data_hora']->format('d/m/Y H:i') }}</p>
                                    </div>
                                @endif

                                @if(isset($morphableInfo['local']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Local</h3>
                                        <p class="text-sm text-gray-900">{{ $morphableInfo['local'] ?? 'N/A' }}</p>
                                    </div>
                                @endif

                                @if(isset($morphableInfo['status']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Status</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($morphableInfo['status'] === 'aprovado') bg-green-100 text-green-800
                                            @elseif($morphableInfo['status'] === 'rejeitado') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($morphableInfo['status']) }}
                                        </span>
                                    </div>
                                @endif

                                @if(isset($morphableInfo['criado_por']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Criado por</h3>
                                        <p class="text-sm text-gray-900">{{ $morphableInfo['criado_por']->nome ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $morphableInfo['criado_por']->email ?? '' }}</p>
                                    </div>
                                @endif

                                @if(isset($morphableInfo['aprovado_por']) && $morphableInfo['aprovado_por'])
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Aprovado por</h3>
                                        <p class="text-sm text-gray-900">{{ $morphableInfo['aprovado_por']->nome ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $morphableInfo['aprovado_por']->email ?? '' }}</p>
                                    </div>
                                @endif
                            </div>

                            @if(isset($morphableInfo['descricao']) && $morphableInfo['descricao'])
                                <div>
                                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Descrição</h3>
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $morphableInfo['descricao'] }}</p>
                                </div>
                            @endif

                            @if(isset($morphableInfo['link']))
                                <div class="pt-2">
                                    <a href="{{ $morphableInfo['link'] }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                                        Ver Atividade
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif

                        @elseif($morphableInfo['tipo'] === 'Convite')
                            <!-- Convite Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Título do Convite</h3>
                                    <p class="text-sm text-gray-900">{{ $morphableInfo['titulo'] }}</p>
                                </div>

                                @if(isset($morphableInfo['estado']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Estado</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($morphableInfo['estado'] === 'aceite') bg-green-100 text-green-800
                                            @elseif($morphableInfo['estado'] === 'rejeitado') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($morphableInfo['estado']) }}
                                        </span>
                                    </div>
                                @endif

                                @if(isset($morphableInfo['remetente']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Remetente</h3>
                                        <p class="text-sm text-gray-900">{{ $morphableInfo['remetente']->nome ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $morphableInfo['remetente']->email ?? '' }}</p>
                                    </div>
                                @endif

                                @if(isset($morphableInfo['data_hora']))
                                    <div>
                                        <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Data e Hora</h3>
                                        <p class="text-sm text-gray-900">{{ $morphableInfo['data_hora']->format('d/m/Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>

                            @if(isset($morphableInfo['descricao']) && $morphableInfo['descricao'])
                                <div>
                                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Descrição</h3>
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $morphableInfo['descricao'] }}</p>
                                </div>
                            @endif

                            @if(isset($morphableInfo['link']) && $morphableInfo['link'])
                                <div class="pt-2">
                                    <a href="{{ $morphableInfo['link'] }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                                        Ver Atividade Relacionada
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif

                        @else
                            <!-- Generic Type -->
                            <div>
                                <p class="text-sm text-gray-900">ID: {{ $morphableInfo['id'] ?? 'N/A' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer Actions -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <a href="{{ route('mensagens.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Voltar para Mensagens
            </a>
            @if(!$notificacao->is_seen)
                <form action="{{ route('notifications.read', $notificacao->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                        Marcar como lida
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

