@php
    use Illuminate\Support\Facades\Storage;
@endphp
@extends('layouts.app')

@section('title', __('settings.title'))

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-800">{{ __('settings.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('settings.subtitle') }}</p>
    </div>

    <!-- Secção: Perfil -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">{{ __('settings.profile_section.title') }}</h2>
        </div>
        <div class="p-6">
            <!-- Aviso Informativo -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-blue-800">
                            {{ __('settings.profile_section.info_message') }}
                        </p>
                        <div class="mt-2">
                            <a 
                                href="{{ route('profile.show') }}" 
                                class="text-sm font-medium text-blue-600 hover:text-blue-700 underline"
                            >
                                {{ __('settings.profile_section.info_link') }} →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Perfil (apenas visualização) -->
            <div class="space-y-4">
                <!-- Nome -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        {{ __('settings.profile_section.name') }}
                    </label>
                    <p class="text-sm text-gray-800">{{ $user->nome }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        {{ __('settings.profile_section.email') }}
                    </label>
                    <p class="text-sm text-gray-800">{{ $user->email }}</p>
                </div>

                <!-- Telefone -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        {{ __('settings.profile_section.phone') }}
                    </label>
                    <p class="text-sm text-gray-800">{{ $user->telefone ?? '-' }}</p>
                </div>

                <!-- Número de Processo -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        {{ __('settings.profile_section.process_number') }}
                    </label>
                    <p class="text-sm text-gray-800">{{ $user->num_processo }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secção: Preferências -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">{{ __('settings.preferences_section.title') }}</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('settings.preferences.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Idioma -->
                <div>
                    <label for="locale" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('settings.preferences_section.language') }}
                    </label>
                    <select 
                        name="locale" 
                        id="locale"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('locale') border-red-300 @enderror"
                    >
                        @foreach(__('settings.languages') as $code => $name)
                            <option value="{{ $code }}" {{ $user->locale === $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('settings.preferences_section.select_language') }}
                    </p>
                    @error('locale')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notificações por Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('settings.preferences_section.email_notifications') }}
                    </label>
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="prefer_email" 
                            id="prefer_email"
                            value="1"
                            {{ $user->prefer_email ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                        >
                        <label for="prefer_email" class="ml-2 block text-sm text-gray-700">
                            {{ __('settings.preferences_section.prefer_email_label') }}
                        </label>
                    </div>
                    @error('prefer_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                    >
                        {{ __('settings.preferences_section.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Secção: Conta -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">{{ __('settings.account_section.title') }}</h2>
        </div>
        <div class="p-6 space-y-6">
            <!-- Estado da Conta -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('settings.account_section.status') }}
                </label>
                <div class="mt-1">
                    @if($user->is_aprovado)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-50 text-green-700 border border-green-200">
                            {{ __('settings.account_section.status_active') }}
                        </span>
                    @elseif($user->is_active && !$user->is_aprovado)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                            {{ __('settings.account_section.status_pending') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-50 text-red-700 border border-red-200">
                            {{ __('settings.account_section.status_inactive') }}
                        </span>
                    @endif
                </div>
                
                @if($user->isEstudante() && !$user->is_aprovado)
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    {{ __('settings.account_section.pending_warning') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Eliminar Conta (TODO) -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">{{ __('settings.account_section.delete_account') }}</h3>
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('settings.account_section.delete_warning') }}
                </p>
                <!-- TODO: Implementar eliminação de conta -->
                <button 
                    type="button"
                    disabled
                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg opacity-50 cursor-not-allowed"
                    title="{{ __('settings.account_section.delete_todo') }}"
                >
                    {{ __('settings.account_section.delete_button') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

