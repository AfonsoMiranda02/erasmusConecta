@extends('layouts.app')

@section('title', 'Configurações')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Configurações</h1>
        <p class="mt-1 text-sm text-gray-500">Gerir preferências e informações da tua conta.</p>
    </div>

    <div class="space-y-6">
        <!-- Secção: Perfil -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Perfil</h3>
            <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-5">
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
                        value="{{ old('nome', $user->nome) }}"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('nome') border-red-300 @enderror"
                    >
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email (apenas leitura) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        value="{{ $user->email }}"
                        disabled
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                    >
                    <p class="mt-1 text-xs text-gray-500">O e-mail não pode ser alterado.</p>
                </div>

                <!-- Botões -->
                <div class="pt-4 flex space-x-3">
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                    >
                        Guardar alterações
                    </button>
                    <a 
                        href="{{ route('settings.edit') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Secção: Segurança -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Segurança</h3>
            <div class="flex items-start space-x-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <svg class="h-5 w-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-blue-800">
                    A gestão da palavra-passe é feita através do sistema de autenticação principal.
                </p>
            </div>
        </div>

        <!-- Secção: Preferências -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Preferências</h3>
            <form method="POST" action="{{ route('settings.preferences.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Receber notificações por email -->
                <div class="flex items-center">
                    <input 
                        id="prefer_email" 
                        name="prefer_email" 
                        type="checkbox" 
                        value="1"
                        {{ old('prefer_email', $user->prefer_email ?? true) ? 'checked' : '' }}
                        class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                    >
                    <label for="prefer_email" class="ml-3 block text-sm text-gray-700">
                        Receber notificações por email
                    </label>
                </div>

                <!-- Idioma -->
                <div>
                    <label for="locale" class="block text-sm font-medium text-gray-700 mb-2">
                        Idioma
                    </label>
                    <select 
                        id="locale" 
                        name="locale"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('locale') border-red-300 @enderror"
                    >
                        <option value="pt_PT" {{ old('locale', $user->locale ?? 'pt_PT') === 'pt_PT' ? 'selected' : '' }}>PT-PT</option>
                        <option value="en" {{ old('locale', $user->locale ?? 'pt_PT') === 'en' ? 'selected' : '' }}>EN</option>
                    </select>
                    @error('locale')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botões -->
                <div class="pt-4 flex space-x-3">
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                    >
                        Guardar alterações
                    </button>
                    <a 
                        href="{{ route('settings.edit') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Secção: Conta -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Conta</h3>
            
            <!-- Estado da Conta -->
            <div class="mb-6">
                <p class="text-sm font-medium text-gray-700 mb-2">Estado da Conta</p>
                <div class="mt-2">
                    @if($user->is_active && $user->is_aprovado)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                            Ativa
                        </span>
                    @elseif(!$user->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                            Desativada
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                            Pendente
                        </span>
                    @endif
                </div>
            </div>

            <!-- Aviso se estudante em mobilidade e pendente -->
            @if(($user->cargo === 'estudante' || $user->cargo === 'intercambista') && !$user->is_aprovado)
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-yellow-800">
                            A tua conta aguarda validação pelo administrador.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Eliminar Conta -->
            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Eliminar Conta</p>
                <p class="text-xs text-gray-500 mb-4">
                    Ao eliminar a tua conta, todos os teus dados serão permanentemente removidos. Esta ação não pode ser desfeita.
                </p>
                <!-- TODO: Implementar lógica de eliminação de conta -->
                <button 
                    type="button"
                    disabled
                    class="px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-lg opacity-50 cursor-not-allowed"
                >
                    Eliminar conta
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

