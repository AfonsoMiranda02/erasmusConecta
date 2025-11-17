@extends('layouts.app')

@section('title', $evento->titulo)

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <a 
                href="{{ route('atividades.index') }}" 
                class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
            >
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar às atividades
            </a>
            <h1 class="text-2xl font-semibold text-gray-800 mt-2">{{ $evento->titulo }}</h1>
            @if($evento->tipo)
            <p class="mt-1 text-sm text-gray-500">{{ $evento->tipo->nome }}</p>
            @endif
        </div>
        <div class="flex items-center space-x-2">
            @can('update', $evento)
            <a 
                href="{{ route('atividades.edit', $evento->id) }}" 
                class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
            >
                Editar
            </a>
            @endcan
            
            @can('delete', $evento)
            <form method="POST" action="{{ route('atividades.destroy', $evento->id) }}" class="inline" onsubmit="return confirm('Tens a certeza que queres eliminar esta atividade?');">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-4 py-2 border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                >
                    Eliminar
                </button>
            </form>
            @endcan

            @can('approve', $evento)
            @if($evento->status === 'pendente')
            <form method="POST" action="{{ route('atividades.approve', $evento->id) }}" class="inline">
                @csrf
                <button 
                    type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                >
                    Aprovar
                </button>
            </form>
            @endif
            @endcan

            @can('reject', $evento)
            @if($evento->status === 'pendente')
            <form method="POST" action="{{ route('atividades.reject', $evento->id) }}" class="inline">
                @csrf
                <button 
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                >
                    Rejeitar
                </button>
            </form>
            @endif
            @endcan

            @can('archive', $evento)
            @if($evento->status !== 'rejeitado')
            <form method="POST" action="{{ route('atividades.archive', $evento->id) }}" class="inline">
                @csrf
                <button 
                    type="submit"
                    class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Arquivar
                </button>
            </form>
            @endif
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Descrição -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Descrição</h2>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $evento->descricao }}</p>
            </div>

            <!-- Informações Detalhadas -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Informações</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Data e Hora</p>
                            <p class="mt-1 text-sm text-gray-800">{{ $evento->data_hora->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($evento->local)
                    <div class="flex items-start">
                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Local</p>
                            <p class="mt-1 text-sm text-gray-800">{{ $evento->local }}</p>
                        </div>
                    </div>
                    @endif

                    @if($evento->capacidade > 0)
                    <div class="flex items-start">
                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Capacidade</p>
                            <p class="mt-1 text-sm text-gray-800">
                                {{ $evento->inscricoes->where('estado', 'aprovada')->where('is_active', true)->count() }} / {{ $evento->capacidade }} inscritos
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Convites (para quem pode criar) -->
            @if($podeCriarConvite)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Convites</h2>
                    <a 
                        href="{{ route('convites.create', $evento->id) }}" 
                        class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                    >
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Novo Convite
                        </span>
                    </a>
                </div>
                @if($convites->isEmpty())
                <p class="text-sm text-gray-500">Ainda não há convites enviados.</p>
                @else
                <div class="space-y-2">
                    @foreach($convites as $conv)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $conv->destinatario->nome ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $conv->destinatario->email ?? 'N/A' }}</p>
                            @if($conv->descricao)
                            <p class="text-xs text-gray-400 mt-1">{{ $conv->descricao }}</p>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                @if($conv->estado === 'aceite') bg-green-50 text-green-700 border border-green-200
                                @elseif($conv->estado === 'pendente') bg-yellow-50 text-yellow-700 border border-yellow-200
                                @elseif($conv->estado === 'recusado') bg-red-50 text-red-700 border border-red-200
                                @else bg-gray-50 text-gray-700 border border-gray-200
                                @endif">
                                {{ ucfirst($conv->estado) }}
                            </span>
                            @can('delete', $conv)
                            <form method="POST" action="{{ route('convites.destroy', $conv->id) }}" class="inline" onsubmit="return confirm('Tens a certeza que queres eliminar este convite?');">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    class="text-red-600 hover:text-red-700 text-sm"
                                    title="Eliminar convite"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            <!-- Inscrições (para Admin e Professor) -->
            @php
                $user = Auth::user();
                $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
            @endphp
            @if($primeiroChar === 'A' || $primeiroChar === 'P')
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Inscrições</h2>
                @if($evento->inscricoes->where('is_active', true)->isEmpty())
                <p class="text-sm text-gray-500">Ainda não há inscrições.</p>
                @else
                <div class="space-y-2">
                    @foreach($evento->inscricoes->where('is_active', true) as $insc)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $insc->user->nome }}</p>
                            <p class="text-xs text-gray-500">{{ $insc->user->email }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                            @if($insc->estado === 'aprovada') bg-green-50 text-green-700 border border-green-200
                            @elseif($insc->estado === 'pendente') bg-yellow-50 text-yellow-700 border border-yellow-200
                            @else bg-red-50 text-red-700 border border-red-200
                            @endif">
                            {{ ucfirst($insc->estado) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Convite Recebido (para Intercambista) -->
            @if($conviteRecebido)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Convite Recebido</h2>
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-medium text-blue-800">Recebeste um convite para esta atividade</p>
                        @if($conviteRecebido->descricao)
                        <p class="text-xs text-blue-600 mt-1">{{ $conviteRecebido->descricao }}</p>
                        @endif
                        <p class="text-xs text-blue-600 mt-1">Estado: {{ ucfirst($conviteRecebido->estado) }}</p>
                    </div>
                    @if($conviteRecebido->estado === 'pendente')
                    <div class="flex space-x-2">
                        <form method="POST" action="{{ route('convites.accept', $conviteRecebido->id) }}" class="flex-1">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            >
                                Aceitar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('convites.reject', $conviteRecebido->id) }}" class="flex-1">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full px-4 py-2 border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            >
                                Rejeitar
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Ações -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Ações</h2>
                @if($inscricao)
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-medium text-blue-800">Já estás inscrito nesta atividade</p>
                        <p class="text-xs text-blue-600 mt-1">Estado: {{ ucfirst($inscricao->estado) }}</p>
                    </div>
                    @if($inscricao->estado === 'pendente' || $inscricao->estado === 'aprovada')
                    <form method="POST" action="#" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            class="w-full px-4 py-2 border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                        >
                            Cancelar Inscrição
                        </button>
                    </form>
                    @endif
                </div>
                @elseif($podeInscrever)
                <form method="POST" action="#" class="w-full">
                    @csrf
                    <button 
                        type="submit"
                        class="w-full px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                    >
                        Inscrever-me
                    </button>
                </form>
                @else
                <p class="text-sm text-gray-500">Não podes inscrever-te nesta atividade.</p>
                @endif
            </div>

            <!-- Criador -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Organizador</h2>
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium text-sm">
                        {{ $evento->criador->iniciais ?? 'N/A' }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">{{ $evento->criador->nome ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $evento->criador->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Status (para Admin) -->
            @if(Auth::user()->isAdmin())
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Estado</h2>
                <div>
                    @if($evento->status === 'pendente')
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                            Pendente
                        </span>
                    @elseif($evento->status === 'aprovado')
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-green-50 text-green-700 border border-green-200">
                            Aprovado
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-red-50 text-red-700 border border-red-200">
                            Rejeitado
                        </span>
                    @endif
                </div>
                @if($evento->aprovador)
                <p class="mt-3 text-xs text-gray-500">
                    Aprovado por: <span class="font-medium">{{ $evento->aprovador->nome }}</span>
                </p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

