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
                <!-- User info -->
                <div class="flex items-center space-x-3">
                    <!-- Avatar -->
                    @php
                        $user = Auth::user();
                    @endphp
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
                    
                    <!-- User details -->
                    <div class="hidden sm:block">
                        <div class="text-sm font-medium text-gray-800">{{ $user->nome }}</div>
                    </div>
                </div>

                <!-- Logout button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button 
                        type="submit"
                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-md focus:outline-none transition-colors"
                        title="Sair"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

