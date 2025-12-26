@extends('layouts.admin')

@section('title', 'Documentos Submetidos')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Documentos Submetidos</h1>
        <p class="mt-1 text-sm text-gray-500">Gerir documentos submetidos pelos estudantes em mobilidade</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white border border-gray-200 rounded-lg p-4">
        <form method="GET" action="{{ route('admin.documentos.index') }}" class="flex flex-wrap items-end gap-4">
            <!-- Filtro por Estado -->
            <div class="flex-1 min-w-[200px]">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <select 
                    id="estado" 
                    name="estado"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                >
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('estado') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="aprovado" {{ request('estado') === 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                    <option value="rejeitado" {{ request('estado') === 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                </select>
            </div>

            <!-- Filtro por Utilizador -->
            <div class="flex-1 min-w-[200px]">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Utilizador
                </label>
                <select 
                    id="user_id" 
                    name="user_id"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                >
                    <option value="">Todos</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->nome }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botões -->
            <div class="flex space-x-2">
                <button 
                    type="submit"
                    class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                >
                    Filtrar
                </button>
                <a 
                    href="{{ route('admin.documentos.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                >
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Documentos -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        @if($documentos->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Nenhum documento encontrado.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo de Documento
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilizador
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data de Submissão
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($documentos as $documento)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $documento->morph_type }}</div>
                                    <div class="text-xs text-gray-500">{{ $documento->file_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $documento->user->nome }}</div>
                                    <div class="text-xs text-gray-500">{{ $documento->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $documento->dh_entrega->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a 
                                            href="{{ route('admin.documentos.show', $documento->id) }}"
                                            class="text-teal-600 hover:text-teal-900"
                                            title="Ver detalhes"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if($documento->estado === 'pendente')
                                            <form method="POST" action="{{ route('admin.documentos.approve', $documento->id) }}" class="inline">
                                                @csrf
                                                <button 
                                                    type="submit"
                                                    class="text-green-600 hover:text-green-900"
                                                    title="Aprovar"
                                                    onclick="return confirm('Tens a certeza que queres aprovar este documento?')"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <button 
                                                type="button"
                                                onclick="openRejectModal({{ $documento->id }})"
                                                class="text-red-600 hover:text-red-900"
                                                title="Rejeitar"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $documentos->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal de Rejeição -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Rejeitar Documento</h3>
        <form method="POST" id="rejectForm" class="space-y-4">
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
function openRejectModal(documentoId) {
    const form = document.getElementById('rejectForm');
    form.action = '{{ route("admin.documentos.reject", ":id") }}'.replace(':id', documentoId);
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('mensagem_rejeicao').value = '';
}
</script>
@endsection

