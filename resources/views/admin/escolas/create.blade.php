@extends('layouts.admin')

@section('title', 'Criar Escola')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <a 
            href="{{ route('admin.escolas.index') }}" 
            class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
        >
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar às escolas
        </a>
        <h1 class="text-2xl font-semibold text-gray-800 mt-2">Criar Nova Escola</h1>
        <p class="mt-1 text-sm text-gray-500">Preenche os dados abaixo para criar uma nova escola</p>
    </div>

    <!-- Formulário -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="POST" action="{{ route('admin.escolas.store') }}" class="space-y-6">
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
                    placeholder="Ex: Escola Superior de Tecnologia e Gestão"
                >
                @error('nome')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instituição -->
            <div>
                <label for="instituicao" class="block text-sm font-medium text-gray-700 mb-2">
                    Instituição <span class="text-red-500">*</span>
                </label>
                <input 
                    id="instituicao" 
                    name="instituicao" 
                    type="text" 
                    required 
                    value="{{ old('instituicao') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('instituicao') border-red-300 @enderror"
                    placeholder="Ex: IPVC"
                >
                @error('instituicao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Morada -->
            <div>
                <label for="morada" class="block text-sm font-medium text-gray-700 mb-2">
                    Morada <span class="text-red-500">*</span>
                </label>
                <input 
                    id="morada" 
                    name="morada" 
                    type="text" 
                    required 
                    value="{{ old('morada') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('morada') border-red-300 @enderror"
                    placeholder="Ex: Rua da Escola"
                >
                @error('morada')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Código Postal -->
            <div>
                <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-2">
                    Código Postal <span class="text-red-500">*</span>
                </label>
                <input 
                    id="codigo_postal" 
                    name="codigo_postal" 
                    type="text" 
                    required 
                    value="{{ old('codigo_postal') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('codigo_postal') border-red-300 @enderror"
                    placeholder="Ex: 4900-000"
                >
                @error('codigo_postal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Abreviação -->
            <div>
                <label for="abreviacao" class="block text-sm font-medium text-gray-700 mb-2">
                    Abreviação <span class="text-red-500">*</span>
                </label>
                <input 
                    id="abreviacao" 
                    name="abreviacao" 
                    type="text" 
                    required 
                    value="{{ old('abreviacao') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('abreviacao') border-red-300 @enderror"
                    placeholder="Ex: ESTG"
                >
                @error('abreviacao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a 
                    href="{{ route('admin.escolas.index') }}" 
                    class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Cancelar
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                >
                    Criar Escola
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

