@php
    $currentRoute = request()->route()->getName();
    $user = Auth::user();
@endphp

<!-- Sidebar -->
<aside 
    id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-gray-50 border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
>
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center h-14 px-6 border-b border-gray-200">
            <h1 class="text-lg font-semibold text-teal-700">ErasmusConecta</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a 
                href="{{ route('dashboard') }}" 
                class="flex items-center px-3 py-2.5 rounded-md transition-colors {{ $currentRoute === 'dashboard' ? 'bg-blue-50 text-teal-700 border-l-2 border-teal-600' : 'text-gray-700 hover:bg-gray-100 hover:text-teal-700' }}"
            >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-sm font-medium">Início</span>
            </a>

            <!-- Perfil -->
            <a 
                href="{{ route('profile.show') }}" 
                class="flex items-center px-3 py-2.5 rounded-md transition-colors {{ $currentRoute === 'profile.show' ? 'bg-blue-50 text-teal-700 border-l-2 border-teal-600' : 'text-gray-700 hover:bg-gray-100 hover:text-teal-700' }}"
            >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-sm font-medium">O meu Perfil</span>
            </a>

            <!-- Atividades / Mobilidades -->
            <a 
                href="{{ route('atividades.index') }}" 
                class="flex items-center px-3 py-2.5 rounded-md transition-colors {{ in_array($currentRoute, ['atividades.index', 'atividades.show']) ? 'bg-blue-50 text-teal-700 border-l-2 border-teal-600' : 'text-gray-700 hover:bg-gray-100 hover:text-teal-700' }}"
            >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium">Atividades</span>
            </a>

            <!-- Candidaturas Erasmus -->
            <a 
                href="#" 
                class="flex items-center px-3 py-2.5 rounded-md transition-colors text-gray-700 hover:bg-gray-100 hover:text-teal-700"
            >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium">Candidaturas</span>
            </a>

            <!-- Instituições Parceiras -->
            <a 
                href="#" 
                class="flex items-center px-3 py-2.5 rounded-md transition-colors text-gray-700 hover:bg-gray-100 hover:text-teal-700"
            >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-sm font-medium">Instituições Parceiras</span>
            </a>

            <!-- Mensagens / Contactos -->
            <a 
                href="#" 
                class="flex items-center px-3 py-2.5 rounded-md transition-colors text-gray-700 hover:bg-gray-100 hover:text-teal-700"
            >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium">Mensagens</span>
                @php
                    $unreadCount = \App\Models\notificacoes::where('user_id', $user->id)->where('is_seen', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="ml-auto bg-red-100 text-red-700 text-xs font-semibold px-1.5 py-0.5 rounded-full border border-red-200">{{ $unreadCount }}</span>
                @endif
            </a>

            <!-- Documentos / Uploads -->
            <a 
                href="#" 
                class="flex items-center px-3 py-2.5 rounded-md transition-colors text-gray-700 hover:bg-gray-100 hover:text-teal-700"
            >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span class="text-sm font-medium">Documentos</span>
            </a>

            <!-- Configurações -->
            <div class="pt-4 mt-4 border-t border-gray-200">
                <a 
                    href="#" 
                    class="flex items-center px-3 py-2.5 rounded-md transition-colors text-gray-700 hover:bg-gray-100 hover:text-teal-700"
                >
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium">Configurações</span>
                </a>
            </div>
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">© {{ date('Y') }} IPVC</p>
        </div>
    </div>
</aside>

