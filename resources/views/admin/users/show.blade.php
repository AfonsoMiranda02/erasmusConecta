@extends('layouts.admin')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Detalhes do Utilizador')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <a 
                href="{{ route('admin.users.index') }}" 
                class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
            >
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar aos utilizadores
            </a>
            <div class="flex items-center space-x-3 mt-2">
                @if($user->avatar_path)
                    <img class="h-10 w-10 rounded-full" src="{{ Storage::url($user->avatar_path) }}" alt="{{ $user->nome }}">
                @else
                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium text-sm">
                        {{ $user->iniciais }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">{{ $user->nome }}</h1>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a 
                href="{{ route('admin.users.edit', $user->id) }}" 
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                Editar
            </a>
        </div>
    </div>

    <!-- Informações do Utilizador -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Informações</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nome</p>
                <p class="mt-1 text-sm text-gray-900">{{ $user->nome }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</p>
                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Número de Processo</p>
                <p class="mt-1 text-sm text-gray-900">{{ $user->num_processo ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Cargo</p>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        @if($user->cargo === 'admin') bg-purple-100 text-purple-700 border border-purple-200
                        @elseif($user->cargo === 'professor') bg-teal-100 text-teal-700 border border-teal-200
                        @elseif($user->cargo === 'intercambista') bg-green-100 text-green-700 border border-green-200
                        @elseif($user->cargo === 'estudante') bg-orange-100 text-orange-700 border border-orange-200
                        @else bg-gray-100 text-gray-700 border border-gray-200
                        @endif">
                        {{ ucfirst($user->cargo ?? 'N/A') }}
                    </span>
                </p>
            </div>
            @if($user->telefone)
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Telefone</p>
                <p class="mt-1 text-sm text-gray-900">{{ $user->telefone }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Estado</p>
                <div class="mt-1 flex flex-col space-y-1">
                    @if($user->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200 w-fit">
                            Ativo
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200 w-fit">
                            Inativo
                        </span>
                    @endif
                    @if($user->is_aprovado)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 w-fit">
                            Aprovado
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200 w-fit">
                            Pendente
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Atividades Criadas -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Atividades Criadas</h2>
        @if($user->eventosCriados->isEmpty())
            <p class="text-sm text-gray-500">Não há atividades criadas por este utilizador.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($user->eventosCriados as $evento)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evento->titulo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $evento->data_hora ? $evento->data_hora->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($evento->status === 'aprovado') bg-green-50 text-green-700 border border-green-200
                                    @elseif($evento->status === 'pendente') bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @else bg-red-50 text-red-700 border border-red-200
                                    @endif">
                                    {{ ucfirst($evento->status) }}
                                </span>
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

