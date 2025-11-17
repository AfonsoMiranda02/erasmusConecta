@extends('layouts.admin')

@section('title', 'Editar Disciplina')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <a 
            href="{{ route('admin.disciplinas.index') }}" 
            class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
        >
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar às disciplinas
        </a>
        <h1 class="text-2xl font-semibold text-gray-800 mt-2">Editar Disciplina</h1>
        <p class="mt-1 text-sm text-gray-500">Atualiza os dados da disciplina</p>
    </div>

    <!-- Formulário -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="POST" action="{{ route('admin.disciplinas.update', $disciplina->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

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
                    value="{{ old('nome', $disciplina->nome) }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('nome') border-red-300 @enderror"
                >
                @error('nome')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sigla -->
            <div>
                <label for="sigla" class="block text-sm font-medium text-gray-700 mb-2">
                    Sigla <span class="text-red-500">*</span>
                </label>
                <input 
                    id="sigla" 
                    name="sigla" 
                    type="text" 
                    required 
                    value="{{ old('sigla', $disciplina->sigla) }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('sigla') border-red-300 @enderror"
                    maxlength="50"
                >
                @error('sigla')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a 
                    href="{{ route('admin.disciplinas.index') }}" 
                    class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Cancelar
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                >
                    Atualizar Disciplina
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

