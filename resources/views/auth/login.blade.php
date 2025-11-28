@extends('auth.layout')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-1">Entrar</h2>
        <p class="text-sm text-gray-600">Acede à tua conta ErasmusConecta</p>
    </div>

    @if(session('password_reset'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
            {{ session('password_reset') }}
        </div>
    @endif

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                E-mail
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
                    placeholder="exemplo@ipvc.pt"
                >
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Palavra-passe
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
                    autocomplete="current-password" 
                    required
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-300 @enderror"
                    placeholder="••••••••"
                >
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Lembrar-me
                </label>
            </div>

            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Esqueceste-te da palavra-passe?
                </a>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button 
                type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                Entrar
            </button>
        </div>
    </form>

    <!-- Register Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Ainda não tens conta?
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Criar conta
            </a>
        </p>
    </div>
</div>
@endsection

