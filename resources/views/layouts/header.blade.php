@php
    use Illuminate\Support\Facades\Storage;
@endphp
<header class="bg-white border-b border-gray-200 sticky top-0 z-10">
    <div class="px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">
            <!-- Mobile menu button -->
            <button 
                onclick="toggleSidebar()"
                class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Right side: User info -->
            <div class="flex items-center space-x-4 ml-auto">
                <!-- User dropdown -->
                <div class="relative" id="userDropdown">
                    @php
                        $user = Auth::user();
                    @endphp
                    <button 
                        onclick="toggleUserDropdown()"
                        class="flex items-center space-x-3 px-3 py-2 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-colors"
                    >
                        <!-- Avatar -->
                        @if($user->avatar_path)
                            <img 
                                src="{{ Storage::url($user->avatar_path) }}" 
                                alt="{{ $user->nome }}"
                                class="h-8 w-8 rounded-full object-cover border border-gray-200"
                            >
                        @else
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium text-xs">
                                {{ $user->iniciais }}
                            </div>
                        @endif
                        
                        <!-- User name -->
                        <div class="hidden sm:block text-left">
                            <div class="text-sm font-medium text-gray-800">{{ $user->nome }}</div>
                        </div>
                        
                        <!-- Dropdown arrow -->
                        <svg 
                            id="dropdownArrow"
                            class="h-4 w-4 text-gray-500 hidden sm:block transition-transform"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div 
                        id="dropdownMenu"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 hidden"
                    >
                        @if($user->isAdmin())
                        <a 
                            href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            {{ __('common.menu.admin_dashboard') }}
                        </a>
                        @endif
                        
                        <a 
                            href="{{ route('profile.show') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('common.menu.my_profile') }}
                        </a>
                    </div>
                </div>

                <!-- Logout button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button 
                        type="submit"
                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-md focus:outline-none transition-colors"
                        title="{{ __('common.logout_title') }}"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
            
            <script>
                function toggleUserDropdown() {
                    const menu = document.getElementById('dropdownMenu');
                    const arrow = document.getElementById('dropdownArrow');
                    
                    if (menu.classList.contains('hidden')) {
                        menu.classList.remove('hidden');
                        arrow.classList.add('rotate-180');
                    } else {
                        menu.classList.add('hidden');
                        arrow.classList.remove('rotate-180');
                    }
                }

                // Fechar dropdown ao clicar fora
                document.addEventListener('click', function(event) {
                    const dropdown = document.getElementById('userDropdown');
                    const menu = document.getElementById('dropdownMenu');
                    const arrow = document.getElementById('dropdownArrow');
                    
                    if (!dropdown.contains(event.target)) {
                        menu.classList.add('hidden');
                        arrow.classList.remove('rotate-180');
                    }
                });
            </script>
        </div>
    </div>
</header>

