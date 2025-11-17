@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard de Administração</h1>
        <p class="mt-1 text-sm text-gray-500">Visão geral do sistema ErasmusConecta</p>
    </div>

    <!-- Stats Cards - Utilizadores -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 mb-8">
        <!-- Total de Utilizadores -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Utilizadores</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Admins -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Admins</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalAdmins }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Professores -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Professores</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalProfessores }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Intercambistas -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Intercambistas</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalIntercambistas }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Estudantes -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Estudantes</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalEstudantes }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards - Atividades -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <!-- Total de Atividades -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Atividades</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalAtividades }}</p>
                </div>
            </div>
        </div>

        <!-- Atividades Públicas -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Públicas</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalAtividadesPublic }}</p>
                </div>
            </div>
        </div>

        <!-- Atividades por Convite -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-teal-300 transition-colors">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5">
                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Por Convite</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalAtividadesPrivadas }}</p>
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
                <!-- Gestão de Utilizadores -->
                <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-blue-50 transition-colors group">
                    <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5 group-hover:bg-teal-50 transition-colors">
                        <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800 group-hover:text-teal-700">Gestão de Utilizadores</p>
                        <p class="text-xs text-gray-500 mt-0.5">Gerir utilizadores do sistema</p>
                    </div>
                </a>

                <!-- Gestão de Atividades -->
                <a href="{{ route('atividades.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-blue-50 transition-colors group">
                    <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5 group-hover:bg-teal-50 transition-colors">
                        <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800 group-hover:text-teal-700">Gestão de Atividades</p>
                        <p class="text-xs text-gray-500 mt-0.5">Ver e gerir todas as atividades</p>
                    </div>
                </a>

                <!-- Gestão de Convites -->
                <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-blue-50 transition-colors group">
                    <div class="flex-shrink-0 bg-blue-50 rounded-lg p-2.5 group-hover:bg-teal-50 transition-colors">
                        <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800 group-hover:text-teal-700">Gestão de Convites</p>
                        <p class="text-xs text-gray-500 mt-0.5">Ver e gerir convites</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes e Convites Pendentes -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Atividades Recentes -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-800">Atividades Recentes</h2>
                    <a href="{{ route('atividades.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                        Ver todas
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @if($atividadesRecentes->isEmpty())
                    <div class="p-4 text-center text-sm text-gray-500">
                        Ainda não há atividades.
                    </div>
                @else
                    @foreach($atividadesRecentes as $atividade)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $atividade->titulo }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Criado por: {{ $atividade->criador->nome ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $atividade->created_at ? $atividade->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                @if($atividade->is_public)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                        Pública
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        Por Convite
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Convites Pendentes -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-800">Convites Pendentes</h2>
                    <span class="text-xs text-gray-500">{{ $convitesPendentes->count() }} pendente(s)</span>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @if($convitesPendentes->isEmpty())
                    <div class="p-4 text-center text-sm text-gray-500">
                        Não há convites pendentes.
                    </div>
                @else
                    @foreach($convitesPendentes as $convite)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $convite->evento->titulo ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Intercambista: {{ $convite->destinatario->nome ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Convidado por: {{ $convite->remetente->nome ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $convite->created_at ? $convite->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    Pendente
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
