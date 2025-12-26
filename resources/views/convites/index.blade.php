@extends('layouts.app')

@section('title', __('convites.index_title'))

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">{{ __('convites.index_title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('convites.manage_invitations') }}</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros por Estado -->
    <div class="bg-white border border-gray-200 rounded-lg p-4">
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-700">{{ __('convites.filter_by_status') }}</span>
            <a href="{{ route('convites.index') }}" class="text-sm px-3 py-1 rounded {{ request('estado') == null ? 'bg-teal-100 text-teal-700' : 'text-gray-600 hover:bg-gray-100' }}">
                {{ __('common.all') }}
            </a>
            <a href="{{ route('convites.index', ['estado' => 'pendente']) }}" class="text-sm px-3 py-1 rounded {{ request('estado') == 'pendente' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }}">
                {{ __('convites.status.pending') }} ({{ $convites->where('estado', 'pendente')->count() }})
            </a>
            <a href="{{ route('convites.index', ['estado' => 'aceite']) }}" class="text-sm px-3 py-1 rounded {{ request('estado') == 'aceite' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
                {{ __('convites.status.accepted') }}
            </a>
            <a href="{{ route('convites.index', ['estado' => 'recusado']) }}" class="text-sm px-3 py-1 rounded {{ request('estado') == 'recusado' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-100' }}">
                {{ __('convites.status.rejected') }}
            </a>
        </div>
    </div>

    <!-- Lista de Convites -->
    @if($convites->isEmpty())
        <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-500">{{ __('convites.empty.no_invitations') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($convites as $convite)
                @if(request('estado') && $convite->estado !== request('estado'))
                    @continue
                @endif
                <div class="bg-white border border-gray-200 rounded-lg hover:border-teal-300 transition-colors overflow-hidden">
                    <!-- Header do Card -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $convite->titulo }}</h3>
                                @if($convite->evento)
                                <p class="mt-1 text-sm text-gray-500">{{ $convite->evento->tipo->nome ?? 'Sem tipo' }}</p>
                                @endif
                            </div>
                            <div class="ml-4">
                                @if($convite->estado === 'pendente')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        {{ __('convites.status.pending') }}
                                    </span>
                                @elseif($convite->estado === 'aceite')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                        {{ __('convites.status.accepted') }}
                                    </span>
                                @elseif($convite->estado === 'recusado')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                        {{ __('convites.status.rejected') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                        {{ ucfirst($convite->estado) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo do Card -->
                    <div class="px-6 py-4 space-y-4">
                        @if($convite->descricao)
                        <div>
                            <p class="text-sm text-gray-600 italic">{{ $convite->descricao }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">{{ __('convites.date_time') }}</p>
                                <p class="font-medium text-gray-800">{{ $convite->data_hora->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($convite->evento && $convite->evento->local)
                            <div>
                                <p class="text-gray-500">{{ __('convites.location') }}</p>
                                <p class="font-medium text-gray-800">{{ $convite->evento->local }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-gray-500">{{ __('convites.invited_by') }}</p>
                                <p class="font-medium text-gray-800">{{ $convite->remetente->nome ?? __('common.not_available') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('convites.received_on') }}</p>
                                <p class="font-medium text-gray-800">{{ $convite->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($convite->evento && $convite->evento->descricao)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('convites.activity_description') }}</p>
                            <p class="text-sm text-gray-700">{{ Str::limit($convite->evento->descricao, 150) }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Footer do Card -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <a 
                                href="{{ route('atividades.show', $convite->evento_id) }}" 
                                class="text-sm font-medium text-teal-600 hover:text-teal-700 transition-colors"
                            >
                                {{ __('convites.view_activity') }} →
                            </a>
                            @if($convite->estado === 'pendente')
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('convites.accept', $convite->id) }}" class="inline">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                                    >
                                        {{ __('convites.actions.accept') }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('convites.reject', $convite->id) }}" class="inline">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                    >
                                        {{ __('convites.actions.reject') }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection


