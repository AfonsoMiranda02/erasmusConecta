@extends('layouts.admin')

@section('title', 'Detalhes do Documento')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Detalhes do Documento</h1>
            <p class="mt-1 text-sm text-gray-500">Informações e ações sobre o documento submetido</p>
        </div>
        <a 
            href="{{ route('admin.documentos.index') }}"
            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
        >
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações do Documento -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Detalhes -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Informações do Documento</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Documento</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $documento->morph_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome do Ficheiro</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $documento->file_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Submissão</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $documento->dh_entrega->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
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
                        </dd>
                    </div>
                    @if($documento->estado === 'rejeitado' && $documento->mensagem_rejeicao)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Motivo da Rejeição</dt>
                            <dd class="mt-1 text-sm text-gray-900 p-3 bg-red-50 border border-red-200 rounded-lg">
                                {{ $documento->mensagem_rejeicao }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Informações do Utilizador -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Utilizador</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $documento->user->nome }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $documento->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Número de Processo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $documento->user->num_processo }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Ações -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Ações</h3>
                <div class="space-y-3">
                    <!-- Download -->
                    <a 
                        href="{{ route('admin.documentos.download', $documento->id) }}"
                        class="w-full flex items-center justify-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                    >
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Transferir Ficheiro
                    </a>

                    @if($documento->estado === 'pendente')
                        <!-- Aprovar -->
                        <form method="POST" action="{{ route('admin.documentos.approve', $documento->id) }}">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                                onclick="return confirm('Tens a certeza que queres aprovar este documento?')"
                            >
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Aprovar
                            </button>
                        </form>

                        <!-- Rejeitar -->
                        <button 
                            type="button"
                            onclick="openRejectModal()"
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                        >
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Rejeitar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rejeição -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Rejeitar Documento</h3>
        <form method="POST" action="{{ route('admin.documentos.reject', $documento->id) }}" class="space-y-4">
            @csrf
            <div>
                <label for="mensagem_rejeicao" class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo da Rejeição <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="mensagem_rejeicao" 
                    name="mensagem_rejeicao" 
                    rows="4"
                    required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('mensagem_rejeicao') border-red-300 @enderror"
                    placeholder="Explica o motivo da rejeição para que o estudante possa corrigir e reenviar..."
                ></textarea>
                @error('mensagem_rejeicao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex space-x-3 pt-4">
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                >
                    Rejeitar
                </button>
                <button 
                    type="button"
                    onclick="closeRejectModal()"
                    class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('mensagem_rejeicao').value = '';
}
</script>
@endsection

