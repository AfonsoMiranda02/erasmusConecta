@extends('auth.layout')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-1">{{ __('auth.register.title') }}</h2>
        <p class="text-sm text-gray-600">{{ __('auth.register.subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5" id="registerForm">
        @csrf

        <!-- Tipo de Utilizador -->
        <div>
            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.user_type') }} <span class="text-red-500">*</span>
            </label>
            <select 
                id="tipo" 
                name="tipo" 
                required
                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tipo') border-red-300 @enderror"
            >
                <option value="">{{ __('auth.register.select_type') }}</option>
                <option value="estudante" {{ old('tipo') === 'estudante' ? 'selected' : '' }}>{{ __('auth.register.student') }}</option>
                <option value="professor" {{ old('tipo') === 'professor' ? 'selected' : '' }}>{{ __('auth.register.professor') }}</option>
                <option value="intercambista" {{ old('tipo') === 'intercambista' ? 'selected' : '' }}>{{ __('auth.register.intercambista') }}</option>
            </select>
            @error('tipo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nome -->
        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.name') }} <span class="text-red-500">*</span>
            </label>
            <input 
                id="nome" 
                name="nome" 
                type="text" 
                required 
                value="{{ old('nome') }}"
                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nome') border-red-300 @enderror"
                placeholder="{{ __('auth.register.name_placeholder') }}"
            >
            @error('nome')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.email') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    autocomplete="email" 
                    required 
                    value="{{ old('email') }}"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-300 @enderror"
                    placeholder="{{ __('auth.register.email_placeholder') }}"
                >
            </div>
            <p class="mt-1 text-xs text-gray-500" id="emailHint">
                {{ __('auth.register.email_hint_student') }}
            </p>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Número de Processo -->
        <div>
            <label for="num_processo" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.process_number') }} <span class="text-red-500">*</span>
            </label>
            <input 
                id="num_processo" 
                name="num_processo" 
                type="text" 
                required 
                value="{{ old('num_processo') }}"
                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('num_processo') border-red-300 @enderror"
                placeholder="{{ __('auth.register.process_number_placeholder') }}"
            >
            @error('num_processo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.password') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    autocomplete="new-password" 
                    required
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-300 @enderror"
                    placeholder="{{ __('auth.register.password_placeholder') }}"
                >
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.password_confirm') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    autocomplete="new-password" 
                    required
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="{{ __('auth.register.password_confirm_placeholder') }}"
                >
            </div>
        </div>

        <!-- Campos específicos para Intercambista -->
        <div id="intercambistaFields" class="hidden space-y-5 border-t pt-5">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800 mb-3">
                    <strong>{{ __('common.note') }}:</strong> {{ __('auth.register.intercambista_note') }}
                </p>
            </div>

            <!-- Código de Mobilidade -->
            <div>
                <label for="codigo_mobilidade" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('auth.register.mobility_code') }}
                </label>
                <input 
                    id="codigo_mobilidade" 
                    name="codigo_mobilidade" 
                    type="text" 
                    value="{{ old('codigo_mobilidade') }}"
                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('codigo_mobilidade') border-red-300 @enderror"
                    placeholder="{{ __('auth.register.mobility_code_placeholder') }}"
                >
                @error('codigo_mobilidade')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documento -->
            <div>
                <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('auth.register.document') }}
                </label>
                <input 
                    id="documento" 
                    name="documento" 
                    type="file" 
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('documento') border-red-300 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500">{{ __('auth.register.document_hint') }}</p>
                @error('documento')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button 
                type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                {{ __('auth.register.submit') }}
            </button>
        </div>
    </form>

    <!-- Login Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            {{ __('auth.register.has_account') }}
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                {{ __('auth.register.login') }}
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const intercambistaFields = document.getElementById('intercambistaFields');
    const emailInput = document.getElementById('email');
    const emailHint = document.getElementById('emailHint');

    tipoSelect.addEventListener('change', function() {
        if (this.value === 'intercambista') {
            intercambistaFields.classList.remove('hidden');
            emailHint.textContent = '{{ __('auth.register.email_hint_intercambista') }}';
        } else {
            intercambistaFields.classList.add('hidden');
            if (this.value === 'estudante' || this.value === 'professor') {
                emailHint.textContent = '{{ __('auth.register.email_hint_student') }}';
            } else {
                emailHint.textContent = '';
            }
        }
    });

    // Trigger on page load if tipo is already selected
    if (tipoSelect.value === 'intercambista') {
        intercambistaFields.classList.remove('hidden');
    }
});
</script>
@endsection

