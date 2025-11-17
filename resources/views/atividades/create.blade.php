@extends('layouts.app')

@section('title', 'Criar Atividade')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
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
        <h1 class="text-2xl font-semibold text-gray-800 mt-2">Criar Nova Atividade</h1>
        <p class="mt-1 text-sm text-gray-500">Preenche os dados abaixo para criar uma nova atividade</p>
    </div>

    <!-- Formulário -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="POST" action="{{ route('atividades.store') }}" class="space-y-6">
            @csrf

            <!-- Título -->
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                    Título <span class="text-red-500">*</span>
                </label>
                <input 
                    id="titulo" 
                    name="titulo" 
                    type="text" 
                    required 
                    value="{{ old('titulo') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('titulo') border-red-300 @enderror"
                    placeholder="Ex: Workshop de Programação Web"
                >
                @error('titulo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo -->
            <div>
                <label for="tipo_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Atividade <span class="text-red-500">*</span>
                </label>
                <select 
                    id="tipo_id" 
                    name="tipo_id" 
                    required
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('tipo_id') border-red-300 @enderror"
                >
                    <option value="">Seleciona um tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_id') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="descricao" 
                    name="descricao" 
                    rows="5"
                    required
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('descricao') border-red-300 @enderror"
                    placeholder="Descreve a atividade em detalhe..."
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Data e Hora -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="data_hora" class="block text-sm font-medium text-gray-700 mb-2">
                        Data e Hora <span class="text-red-500">*</span>
                    </label>
                    <input 
                        id="data_hora" 
                        name="data_hora" 
                        type="datetime-local" 
                        required
                        value="{{ old('data_hora') }}"
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('data_hora') border-red-300 @enderror"
                    >
                    @error('data_hora')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacidade -->
                <div>
                    <label for="capacidade" class="block text-sm font-medium text-gray-700 mb-2">
                        Capacidade (opcional)
                    </label>
                    <input 
                        id="capacidade" 
                        name="capacidade" 
                        type="number" 
                        min="0"
                        value="{{ old('capacidade') }}"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('capacidade') border-red-300 @enderror"
                        placeholder="0 = ilimitado"
                    >
                    <p class="mt-1 text-xs text-gray-500">Deixa em branco ou coloca 0 para capacidade ilimitada</p>
                    @error('capacidade')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Local -->
            <div>
                <label for="local" class="block text-sm font-medium text-gray-700 mb-2">
                    Local <span class="text-red-500">*</span>
                </label>
                <input 
                    id="local" 
                    name="local" 
                    type="text" 
                    required
                    value="{{ old('local') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('local') border-red-300 @enderror"
                    placeholder="Ex: Sala 201 ESTG"
                >
                @error('local')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Visibilidade -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Visibilidade
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="is_public" 
                            value="1" 
                            {{ old('is_public', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300"
                        >
                        <span class="ml-2 text-sm text-gray-700">Pública (disponível para todos)</span>
                    </label>
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="is_public" 
                            value="0" 
                            {{ old('is_public') === '0' || old('is_public') === false ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300"
                        >
                        <span class="ml-2 text-sm text-gray-700">Por convite (apenas para utilizadores convidados)</span>
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Atividades por convite só são visíveis para os utilizadores que receberem um convite.</p>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a 
                    href="{{ route('atividades.index') }}" 
                    class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Cancelar
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                >
                    Criar Atividade
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

