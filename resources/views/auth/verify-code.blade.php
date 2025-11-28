@extends('auth.layout')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-1">Verificar Código</h2>
        <p class="text-sm text-gray-600">Introduz o código de 6 dígitos enviado para <strong>{{ $email }}</strong></p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.verify') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">

        <!-- Código de Verificação -->
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                Código de Verificação <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="code" 
                name="code" 
                required
                autofocus
                maxlength="6"
                pattern="[0-9]{6}"
                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-center text-2xl tracking-widest font-mono @error('code') border-red-300 @enderror"
                placeholder="000000"
                autocomplete="off"
            >
            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-gray-500">
                O código expira em 15 minutos. Se não recebeste o código, verifica a pasta de spam ou regista-te novamente.
            </p>
        </div>

        <!-- Botões -->
        <div class="flex items-center justify-between">
            <a 
                href="{{ route('register') }}" 
                class="text-sm text-gray-600 hover:text-gray-900 transition-colors"
            >
                ← Voltar ao registo
            </a>
            <button 
                type="submit"
                class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
            >
                Verificar e Criar Conta
            </button>
        </div>
    </form>
</div>

<script>
    // Auto-focus e formatação do código
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        
        // Permitir apenas números
        codeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            // Auto-submit quando tiver 6 dígitos (opcional)
            if (e.target.value.length === 6) {
                // Podes descomentar para auto-submit
                // e.target.form.submit();
            }
        });

        // Focar no campo
        codeInput.focus();
    });
</script>
@endsection

