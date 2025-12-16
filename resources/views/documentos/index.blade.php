@extends('layouts.app')

@section('title', 'Documentos')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Documentos</h1>
        <p class="mt-1 text-sm text-gray-500">Submete e gere os teus documentos de mobilidade</p>
    </div>

    <!-- Formulário de Submissão -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Submeter Novo Documento</h3>
        <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Tipo de Documento -->
            <div>
                <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <input 
                    id="tipo_documento" 
                    name="tipo_documento" 
                    type="text" 
                    required 
                    value="{{ old('tipo_documento') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('tipo_documento') border-red-300 @enderror"
                    placeholder="Ex: Passaporte, Visto, Seguro de Saúde, Contrato de Mobilidade..."
                >
                @error('tipo_documento')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ficheiro -->
            <div>
                <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Ficheiro <span class="text-red-500">*</span>
                </label>
                <input 
                    id="documento" 
                    name="documento" 
                    type="file" 
                    required
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-teal-700 hover:file:bg-blue-100 @error('documento') border-red-300 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">Formatos aceites: PDF, DOC, DOCX, JPG, JPEG, PNG. Máximo 10MB.</p>
                @error('documento')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button 
                    type="submit"
                    class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                >
                    Submeter Documento
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Documentos -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Documentos Submetidos</h3>
        
        @if($documentos->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Ainda não submeteste nenhum documento.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($documentos as $documento)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-teal-300 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h4 class="text-sm font-semibold text-gray-800">{{ $documento->morph_type }}</h4>
                                    @if($documento->estado === 'pendente')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                            Pendente
                                        </span>
                                    @elseif($documento->estado === 'aprovado')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            Aprovado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                            Rejeitado
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Ficheiro: <span class="font-medium">{{ $documento->file_name }}</span>
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Submetido em: {{ $documento->dh_entrega->format('d/m/Y H:i') }}
                                </p>
                                
                                @if($documento->estado === 'rejeitado' && $documento->mensagem_rejeicao)
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-xs font-medium text-red-800 mb-1">Motivo da Rejeição:</p>
                                        <p class="text-sm text-red-700">{{ $documento->mensagem_rejeicao }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            @if($documento->estado === 'rejeitado')
                                <div class="ml-4">
                                    <form method="POST" action="{{ route('documentos.resubmit', $documento->id) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <div>
                                            <input 
                                                type="file" 
                                                name="documento" 
                                                required
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-teal-700 hover:file:bg-blue-100"
                                            >
                                        </div>
                                        <button 
                                            type="submit"
                                            class="w-full px-3 py-1.5 bg-teal-600 text-white text-xs font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                                        >
                                            Reenviar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

