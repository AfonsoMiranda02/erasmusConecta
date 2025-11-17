@extends('layouts.app')

@section('title', 'Editar Atividade')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <a 
            href="{{ route('atividades.show', $evento->id) }}" 
            class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
        >
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar à atividade
        </a>
        <h1 class="text-2xl font-semibold text-gray-800 mt-2">Editar Atividade</h1>
        <p class="mt-1 text-sm text-gray-500">Atualiza os dados da atividade</p>
    </div>

    <!-- Formulário -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="POST" action="{{ route('atividades.update', $evento->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

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
                    value="{{ old('titulo', $evento->titulo) }}"
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
                        <option value="{{ $tipo->id }}" {{ old('tipo_id', $evento->tipo_id) == $tipo->id ? 'selected' : '' }}>
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
                >{{ old('descricao', $evento->descricao) }}</textarea>
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
                        value="{{ old('data_hora', $evento->data_hora ? $evento->data_hora->format('Y-m-d\TH:i') : '') }}"
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
                        value="{{ old('capacidade', $evento->capacidade) }}"
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
                    value="{{ old('local', $evento->local) }}"
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
                            {{ old('is_public', $evento->is_public ?? true) ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300"
                        >
                        <span class="ml-2 text-sm text-gray-700">Pública (disponível para todos)</span>
                    </label>
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="is_public" 
                            value="0" 
                            {{ old('is_public', $evento->is_public ?? true) == false || old('is_public') === '0' ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300"
                        >
                        <span class="ml-2 text-sm text-gray-700">Por convite (apenas para utilizadores convidados)</span>
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Atividades por convite só são visíveis para os utilizadores que receberem um convite.</p>
            </div>

            <!-- Aviso sobre status -->
            @php
                $user = Auth::user();
                $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
                $isAdmin = $primeiroChar === 'A';
            @endphp
            @if(!$isAdmin && $evento->status === 'aprovado')
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Atenção</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Esta atividade está aprovada. Ao editar, o estado voltará a "pendente" e precisará de nova aprovação por um administrador.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a 
                    href="{{ route('atividades.show', $evento->id) }}" 
                    class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Cancelar
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                >
                    Guardar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

