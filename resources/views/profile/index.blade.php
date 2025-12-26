@php
    use Illuminate\Support\Facades\Storage;
@endphp
@extends('layouts.app')

@section('title', __('profile.title'))

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">{{ __('profile.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('profile.subtitle') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Coluna Esquerda: Resumo do Perfil -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <!-- Avatar -->
                <div class="flex flex-col items-center mb-6">
                    <div class="relative">
                        @if($user->avatar_path)
                            <img 
                                src="{{ Storage::url($user->avatar_path) }}" 
                                alt="{{ $user->nome }}"
                                class="h-24 w-24 rounded-full object-cover border-2 border-gray-200"
                            >
                        @else
                            <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold text-2xl border-2 border-gray-200">
                                {{ $user->iniciais }}
                            </div>
                        @endif
                    </div>
                    <h2 class="mt-4 text-lg font-semibold text-gray-800">{{ $user->nome }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                </div>

                <!-- Informações Principais -->
                <div class="space-y-4 border-t border-gray-200 pt-6">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('profile.summary.process_number') }}</p>
                        <p class="mt-1 text-sm text-gray-800">{{ $user->num_processo }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('profile.summary.status') }}</p>
                        <p class="mt-1">
                            @if($user->is_aprovado)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    {{ __('profile.summary.approved') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    {{ __('profile.summary.pending') }}
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Formulários -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Atualizar Avatar -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">{{ __('profile.avatar.title') }}</h3>
                <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center space-x-4">
                        <div>
                            @if($user->avatar_path)
                                <img 
                                    src="{{ Storage::url($user->avatar_path) }}" 
                                    alt="Avatar atual"
                                    class="h-16 w-16 rounded-full object-cover border border-gray-200"
                                >
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold border border-gray-200">
                                    {{ $user->iniciais }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input 
                                type="file" 
                                name="avatar" 
                                id="avatar"
                                accept="image/jpeg,image/jpg,image/png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-teal-700 hover:file:bg-blue-100 @error('avatar') border-red-300 @enderror"
                            >
                            <p class="mt-1 text-xs text-gray-500">{{ __('profile.avatar.formats') }}</p>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                            >
                                {{ __('profile.avatar.update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Atualizar Dados Pessoais -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">{{ __('profile.personal_info.title') }}</h3>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('profile.personal_info.name') }} <span class="text-red-500">*</span>
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
                            {{ __('profile.personal_info.email') }}
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            value="{{ $user->email }}"
                            disabled
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                        >
                        <p class="mt-1 text-xs text-gray-500">{{ __('profile.personal_info.email_readonly') }}</p>
                    </div>

                    <!-- Telefone -->
                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('profile.personal_info.phone') }}
                        </label>
                        <input
                            id="telefone" 
                            name="telefone" 
                            type="text" 
                            value="{{ old('telefone', $user->telefone) }}"
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('telefone') border-red-300 @enderror"
                            placeholder="{{ __('profile.personal_info.phone_placeholder') }}"
                        >
                        @error('telefone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Processo (apenas leitura) -->
                    <div>
                        <label for="num_processo" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('profile.personal_info.process_number') }}
                        </label>
                        <input 
                            id="num_processo" 
                            type="text" 
                            value="{{ $user->num_processo }}"
                            disabled
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                        >
                        <p class="mt-1 text-xs text-gray-500">{{ __('profile.personal_info.process_readonly') }}</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                        >
                            {{ __('profile.personal_info.save') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Alterar Palavra-passe -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">{{ __('profile.password.title') }}</h3>
                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Password Atual -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('profile.password.current') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                id="current_password" 
                                name="current_password" 
                                type="password" 
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('current_password') border-red-300 @enderror"
                                placeholder="{{ __('profile.password.current_placeholder') }}"
                            >
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nova Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('profile.password.new') }} <span class="text-red-500">*</span>
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
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors @error('password') border-red-300 @enderror"
                                placeholder="{{ __('profile.password.new_placeholder') }}"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('profile.password.confirm') }} <span class="text-red-500">*</span>
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
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                                placeholder="{{ __('profile.password.confirm_placeholder') }}"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
                        >
                            {{ __('profile.password.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

