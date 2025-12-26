@extends('layouts.app')

@section('title', __('atividades.title'))

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ __('atividades.title') }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if($cargo === 'admin')
                    {{ __('atividades.subtitle.admin') }}
                @elseif($cargo === 'professor')
                    {{ __('atividades.subtitle.professor') }}
                @else
                    {{ __('atividades.subtitle.default') }}
                @endif
            </p>
        </div>
        @php
            $user = Auth::user();
            $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
            $podeCriar = in_array($primeiroChar, ['A', 'P', 'E']);
        @endphp
        @if($podeCriar)
        <a 
            href="{{ route('atividades.create') }}" 
            class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
        >
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('atividades.create_button') }}
            </span>
        </a>
        @endif
    </div>

    <!-- Filtros (se necessário) -->
    @if($cargo === 'admin')
    <div class="bg-white border border-gray-200 rounded-lg p-4">
        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700">{{ __('atividades.filters.filter_by_status') }}</label>
            <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <option value="">{{ __('atividades.filters.all') }}</option>
                <option value="pendente">{{ __('atividades.filters.pending') }}</option>
                <option value="aprovado">{{ __('atividades.filters.approved') }}</option>
                <option value="rejeitado">{{ __('atividades.filters.rejected') }}</option>
            </select>
        </div>
    </div>
    @endif

    <!-- Lista de Atividades -->
    @if($eventos->isEmpty())
    <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-4 text-sm font-medium text-gray-900">{{ __('common.not_found') }}</h3>
        <p class="mt-2 text-sm text-gray-500">
            @if($cargo === 'admin' || $cargo === 'professor')
                {{ __('atividades.empty.no_activities_admin') }}
            @else
                {{ __('atividades.empty.no_activities') }}
            @endif
        </p>
    </div>
    @else
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @foreach($eventos as $evento)
        <div class="bg-white border border-gray-200 rounded-lg hover:border-teal-300 transition-colors overflow-hidden">
            <!-- Header do Card -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $evento->titulo }}</h3>
                        @if($evento->tipo)
                        <p class="mt-1 text-sm text-gray-500">{{ $evento->tipo->nome }}</p>
                        @endif
                    </div>
                    @if($cargo === 'admin')
                    <div class="ml-4">
                        @if($evento->status === 'pendente')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                {{ __('atividades.status.pending') }}
                            </span>
                        @elseif($evento->status === 'aprovado')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                {{ __('atividades.status.approved') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                {{ __('atividades.status.rejected') }}
                            </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Conteúdo do Card -->
            <div class="px-6 py-4 space-y-4">
                <!-- Descrição -->
                <p class="text-sm text-gray-600 line-clamp-2">{{ $evento->descricao }}</p>

                <!-- Informações -->
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $evento->data_hora->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($evento->local)
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $evento->local }}</span>
                    </div>
                    @endif
                    @if($evento->capacidade > 0)
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>
                            {{ $evento->inscricoes->where('estado', 'aprovada')->where('is_active', true)->count() }} / {{ $evento->capacidade }} inscritos
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Criador (para Admin e Professor) -->
                @if($cargo === 'admin' || $cargo === 'professor')
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-xs text-gray-500">
                        {{ __('atividades.created_by') }} <span class="font-medium text-gray-700">{{ $evento->criador->nome ?? __('common.not_available') }}</span>
                        @if($evento->aprovador && $evento->status === 'aprovado')
                            • {{ __('atividades.approved_by') }} <span class="font-medium text-gray-700">{{ $evento->aprovador->nome }}</span>
                        @endif
                    </p>
                </div>
                @endif
            </div>

            <!-- Footer do Card -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <a 
                        href="{{ route('atividades.show', $evento->id) }}" 
                        class="text-sm font-medium text-teal-600 hover:text-teal-700 transition-colors"
                    >
                        {{ __('atividades.actions.view') }} →
                    </a>
                    <div class="flex items-center space-x-2">
                        @if(in_array($evento->id, $inscricoesUser))
                        <span class="text-xs text-gray-500">{{ __('atividades.already_registered') }}</span>
                        @elseif($cargo === 'estudante' || $cargo === 'intercambista')
                            @if($evento->status === 'aprovado' && $evento->data_hora >= now())
                            <span class="text-xs text-teal-600 font-medium">{{ __('atividades.available') }}</span>
                            @endif
                        @endif
                        @can('update', $evento)
                        <a 
                            href="{{ route('atividades.edit', $evento->id) }}" 
                            class="text-xs text-gray-500 hover:text-teal-600 transition-colors"
                            title="{{ __('atividades.actions.edit') }}"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

