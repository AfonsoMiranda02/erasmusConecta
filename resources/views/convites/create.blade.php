@extends('layouts.app')

@section('title', 'Convidar para Atividade')

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
        <h1 class="text-2xl font-semibold text-gray-800 mt-2">Convidar para Atividade</h1>
        <p class="mt-1 text-sm text-gray-500">Convidar intercambistas para "{{ $evento->titulo }}"</p>
    </div>

    <!-- Informações da Atividade -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Informações da Atividade</h2>
        <div class="space-y-2">
            <p class="text-sm text-gray-600"><span class="font-medium">Título:</span> {{ $evento->titulo }}</p>
            <p class="text-sm text-gray-600"><span class="font-medium">Data:</span> {{ $evento->data_hora->format('d/m/Y H:i') }}</p>
            @if($evento->local)
            <p class="text-sm text-gray-600"><span class="font-medium">Local:</span> {{ $evento->local }}</p>
            @endif
        </div>
    </div>

    <!-- Formulário de Convite -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Selecionar Intercambistas</h2>
        
        @if($intercambistas->isEmpty())
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">Não há intercambistas disponíveis para convidar.</p>
        </div>
        @else
        <form method="POST" action="{{ route('convites.store', $evento->id) }}" class="space-y-6" id="conviteForm">
            @csrf

            <!-- Campo de Pesquisa -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Pesquisar Intercambista
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="search" 
                        placeholder="Pesquisar por nome ou email..."
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                    >
                </div>
            </div>

            <!-- Lista de Intercambistas -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Intercambistas <span class="text-red-500">*</span>
                    </label>
                    <button 
                        type="button" 
                        onclick="toggleSelectAll()"
                        class="text-xs text-teal-600 hover:text-teal-700 font-medium"
                    >
                        Selecionar todos
                    </button>
                </div>
                <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto bg-gray-50" id="intercambistasList">
                    @php
                        $intercambistasDisponiveis = $intercambistas->filter(function($intercambista) use ($convitesExistentes) {
                            return !in_array($intercambista->id, $convitesExistentes);
                        });
                    @endphp
                    @if($intercambistasDisponiveis->isEmpty())
                    <p class="text-sm text-gray-500 text-center py-4">Todos os intercambistas já foram convidados.</p>
                    @else
                    @foreach($intercambistasDisponiveis as $intercambista)
                    <label class="flex items-center p-3 hover:bg-white rounded-lg cursor-pointer transition-colors intercambista-item border-b border-gray-200 last:border-b-0" 
                           data-nome="{{ strtolower($intercambista->nome) }}" 
                           data-email="{{ strtolower($intercambista->email) }}">
                        <input 
                            type="checkbox" 
                            name="for_user[]" 
                            value="{{ $intercambista->id }}"
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                        >
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $intercambista->nome }}</p>
                            <p class="text-xs text-gray-500">{{ $intercambista->email }}</p>
                        </div>
                    </label>
                    @endforeach
                    @endif
                </div>
                @error('for_user')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('for_user.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if(count($convitesExistentes) > 0)
                <p class="mt-2 text-xs text-gray-500">
                    {{ count($convitesExistentes) }} intercambista(s) já foram convidados para esta atividade e não aparecem na lista.
                </p>
                @endif
                <p class="mt-2 text-xs text-gray-500" id="selectedCount">
                    <span id="count">0</span> intercambista(s) selecionado(s)
                </p>
            </div>

            <!-- Mensagem Personalizada (opcional) -->
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    Mensagem Personalizada (opcional)
                </label>
                <textarea 
                    id="descricao" 
                    name="descricao" 
                    rows="4"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('descricao') border-red-300 @enderror"
                    placeholder="Adiciona uma mensagem personalizada ao convite..."
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

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
                    Enviar Convites
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

<script>
    // Pesquisa de intercambistas
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const items = document.querySelectorAll('.intercambista-item');
            let visibleCount = 0;
            
            items.forEach(item => {
                const nome = item.getAttribute('data-nome');
                const email = item.getAttribute('data-email');
                
                if (searchTerm === '' || nome.includes(searchTerm) || email.includes(searchTerm)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Mostrar mensagem se não houver resultados
            const listContainer = document.getElementById('intercambistasList');
            let noResultsMsg = listContainer.querySelector('.no-results');
            if (visibleCount === 0 && searchTerm !== '') {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('p');
                    noResultsMsg.className = 'no-results text-sm text-gray-500 text-center py-4';
                    noResultsMsg.textContent = 'Nenhum intercambista encontrado.';
                    listContainer.appendChild(noResultsMsg);
                }
                noResultsMsg.style.display = 'block';
            } else if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        });
    }

    // Contador de selecionados
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('input[name="for_user[]"]:checked');
        const count = checkboxes.length;
        const countElement = document.getElementById('count');
        if (countElement) {
            countElement.textContent = count;
        }
    }

    // Selecionar/Desselecionar todos
    function toggleSelectAll() {
        const checkboxes = document.querySelectorAll('input[name="for_user[]"]:not([style*="display: none"])');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('.intercambista-item').style.display !== 'none') {
                checkbox.checked = !allChecked;
            }
        });
        
        updateSelectedCount();
    }

    // Adicionar listeners aos checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name="for_user[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        updateSelectedCount();
    });

    // Validação do formulário
    const form = document.getElementById('conviteForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name="for_user[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Deves selecionar pelo menos um intercambista.');
                return false;
            }
        });
    }
</script>
@endsection
