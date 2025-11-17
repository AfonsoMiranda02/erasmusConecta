@extends('layouts.admin')

@section('title', 'Criar Utilizador')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
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
        <h1 class="text-2xl font-semibold text-gray-800 mt-2">Criar Novo Utilizador</h1>
        <p class="mt-1 text-sm text-gray-500">Preenche os dados abaixo para criar um novo utilizador</p>
    </div>

    <!-- Formulário -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <!-- Nome -->
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome <span class="text-red-500">*</span>
                </label>
                <input 
                    id="nome" 
                    name="nome" 
                    type="text" 
                    required 
                    value="{{ old('nome') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('nome') border-red-300 @enderror"
                    placeholder="Ex: João Silva"
                >
                @error('nome')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    required 
                    value="{{ old('email') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('email') border-red-300 @enderror"
                    placeholder="Ex: joao.silva@ipvc.pt"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Número de Processo -->
            <div>
                <label for="num_processo" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Processo <span class="text-red-500">*</span>
                </label>
                <input 
                    id="num_processo" 
                    name="num_processo" 
                    type="text" 
                    required 
                    value="{{ old('num_processo') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('num_processo') border-red-300 @enderror"
                    placeholder="Ex: A001, P123, I450, E777"
                >
                <p class="mt-1 text-xs text-gray-500">Primeiro carácter define o cargo: A=Admin, P=Professor, I=Intercambista, E=Estudante</p>
                @error('num_processo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Palavra-passe <span class="text-red-500">*</span>
                </label>
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    required 
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('password') border-red-300 @enderror"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Palavra-passe <span class="text-red-500">*</span>
                </label>
                <input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    required 
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                >
            </div>

            <!-- Telefone -->
            <div>
                <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                    Telefone (opcional)
                </label>
                <input 
                    id="telefone" 
                    name="telefone" 
                    type="text" 
                    value="{{ old('telefone') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('telefone') border-red-300 @enderror"
                    placeholder="Ex: 912345678"
                >
                @error('telefone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                        >
                        <span class="ml-2 text-sm text-gray-700">Utilizador Ativo</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_aprovado" 
                            value="1"
                            {{ old('is_aprovado', false) ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                        >
                        <span class="ml-2 text-sm text-gray-700">Aprovado</span>
                    </label>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a 
                    href="{{ route('admin.users.index') }}" 
                    class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Cancelar
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                >
                    Criar Utilizador
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

