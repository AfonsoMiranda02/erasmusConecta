@extends('auth.layout')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-1">Reenviar Email de Verificação</h2>
        <p class="text-sm text-gray-600">Introduz o teu email para receberes um novo link de verificação</p>
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

    <form method="POST" action="{{ route('email.resend') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                required
                autofocus
                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-300 @enderror"
                placeholder="exemplo@ipvc.pt"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botões -->
        <div class="flex items-center justify-between">
            <a 
                href="{{ route('login') }}" 
                class="text-sm text-gray-600 hover:text-gray-900 transition-colors"
            >
                ← Voltar ao login
            </a>
            <button 
                type="submit"
                class="px-6 py-3 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors"
            >
                Reenviar Email
            </button>
        </div>
    </form>
</div>
@endsection

