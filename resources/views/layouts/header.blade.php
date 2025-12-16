@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;

    if (!isset($convites_recentes)) {
        $convites_recentes = \App\Models\convite::where('for_user', Auth::id())
            ->with(['evento', 'remetente'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    if (!isset($notificacoes_recentes)) {
        $notificacoes_recentes = \App\Models\notificacoes::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    $user = Auth::user();
@endphp
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0&icon_names=notifications" />
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
                <span class="material-symbols-outlined" id="enableNotifications" title="Notificações">
                    notifications
                </span>
    @php
    if (!isset($convites_recentes)) {
        $convites_recentes = \App\Models\convite::where('for_user', Auth::id())
            ->with(['evento', 'remetente'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }

    if (!isset($notificacoes_recentes)) {
        $notificacoes_recentes = \App\Models\notificacoes::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }
    @endphp

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
                            Dashboard Admin
                        </a>
                        @endif
                        
                        <a 
                            href="{{ route('profile.show') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Meu Perfil
                        </a>
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

<!-- Modal -->
<div id="notificationsModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold text-gray-800">Notificações</h1>
            <button onclick="document.getElementById('notificationsModal').style.display='none'" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Atividades Recentes -->
    <div class="">
        <!-- Convites e Notificações -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-base font-semibold text-gray-800">Atividades Recentes</h2>
                <button id="markAllReadBtn" class="text-xs text-teal-600 hover:text-teal-700">Marcar todas como lidas</button>
            </div>
            <div id="notificationsList" class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse($convites_recentes->take(4) as $convite)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $convite->titulo }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    De {{ $convite->remetente->nome ?? 'Utilizador' }} • {{ $convite->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if($convite->estado === 'pendente')
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    Novo
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                @endforelse

                @forelse($notificacoes_recentes->take(5) as $notificacao)
                    <div class="p-4 hover:bg-gray-50 transition-colors notification-item {{ !$notificacao->is_seen ? 'bg-blue-50' : '' }}" data-id="{{ $notificacao->id }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $notificacao->titulo }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notificacao->mensagem }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notificacao->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notificacao->is_seen)
                                <span class="ml-2 h-2 w-2 bg-teal-400 rounded-full"></span>
                            @endif
                        </div>
                    </div>
                @empty
                    @if($convites_recentes->isEmpty())
                        <div class="p-4 text-center text-sm text-gray-500">
                            Não há atividades recentes.
                        </div>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/socket.io-client@4/dist/socket.io.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    function urlBase64ToUint8Array(base64String) {
        const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/-/g, "+").replace(/_/g, "/");
        const rawData = window.atob(base64);
        return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
    }

    const icon = document.getElementById("enableNotifications");
    const modal = document.getElementById("notificationsModal");
    const notificationsList = document.getElementById("notificationsList");

    let alreadyEnabled = localStorage.getItem("notifications_enabled") === "1";

    // Função para carregar notificações
    async function loadNotifications() {
        try {
            const res = await fetch("/notifications");
            if (!res.ok) throw new Error("Erro ao buscar notificações");

            const notifications = await res.json();
            const list = document.getElementById("notificationsList");
            
            if (!list) return;

            // Limpar apenas notificações dinâmicas (não as do blade)
            const dynamicItems = list.querySelectorAll('.notification-item[data-dynamic="true"]');
            dynamicItems.forEach(item => item.remove());

            // Adicionar novas notificações se houver
            if (notifications.length === 0 && list.querySelectorAll('.notification-item').length === 0) {
                list.innerHTML = '<div class="p-4 text-center text-sm text-gray-500">Não há atividades recentes.</div>';
            }
        } catch (err) {
            console.error(err);
            const list = document.getElementById("notificationsList");
            if (list && list.querySelectorAll('.notification-item').length === 0) {
                list.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Não foi possível carregar as notificações.</div>';
            }
        }
    }

    icon.addEventListener("click", async () => {

        if (!alreadyEnabled) {
            const permission = await Notification.requestPermission();
            if (permission !== "granted") {
                alert("As notificações foram bloqueadas.");
                return;
            }

            const reg = await navigator.serviceWorker.register("/service-worker.js");
            const sub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array("{{ config('webpush.vapid.public_key') }}")
            });

            await fetch("/push/subscribe", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(sub)
            });

            localStorage.setItem("notifications_enabled", "1");
            alreadyEnabled = true;

            icon.textContent = "notifications"; 
            return;
        }

        modal.style.display = "flex";
        loadNotifications();
    });

    modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

    // Marcar todas como lidas
    const markAllReadBtn = document.getElementById("markAllReadBtn");
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener("click", async () => {
            try {
                const res = await fetch("/notifications/read-all", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
                    }
                });
                if (res.ok) {
                    loadNotifications();
                    // Atualizar contador após marcar todas como lidas
                    if (typeof window.updateUnreadCount === 'function') {
                        window.updateUnreadCount();
                    }
                }
            } catch (err) {
                console.error(err);
            }
        });
    }

    if (alreadyEnabled) {
        icon.textContent = "notifications";
    }

    // A função updateUnreadCount será definida mais abaixo, então não chamamos aqui
    // Ela será chamada automaticamente quando o LaravelEcho estiver pronto
});

    try {
        const userId = {{ $user->id ?? 'null' }};
        if (userId) {
            const echoFactory = window.LaravelEcho || window.Echo;
            const shouldInit = !window.Echo || !window.Echo.connector;

            if (shouldInit && echoFactory) {
                // Detectar o host do LaravelEcho Server
                // Em desenvolvimento Docker, usar localhost:6001
                // Em produção, ajustar conforme necessário
                const isDocker = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
                const echoHost = isDocker 
                    ? 'http://localhost:6001'
                    : (window.location.protocol === 'https:' ? 'https' : 'http') + '://' + window.location.hostname + ':6001';
                
                console.log('Connecting to LaravelEcho Server at:', echoHost);
                
                window.Echo = new echoFactory({
                    broadcaster: 'socket.io',
                    client: window.io,
                    host: echoHost,
                    transports: ['websocket', 'polling'],
                    forceNew: true,
                    reconnection: true,
                    reconnectionDelay: 1000,
                    reconnectionDelayMax: 5000,
                    reconnectionAttempts: Infinity,
                    timeout: 20000,
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    }
                });
                
                // Log de conexão
                window.Echo.connector.socket.on('connect', () => {
                    console.log('LaravelEcho connected successfully');
                    // Atualizar contador quando reconecta
                    if (typeof updateUnreadCount === 'function') {
                        updateUnreadCount();
                    }
                });
                
                window.Echo.connector.socket.on('disconnect', (reason) => {
                    console.log('LaravelEcho disconnected:', reason);
                    // Tentar reconectar automaticamente
                    if (reason === 'io server disconnect') {
                        // Servidor forçou desconexão, reconectar manualmente
                        window.Echo.connector.socket.connect();
                    }
                });
                
                window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
                    console.log('LaravelEcho reconnected after', attemptNumber, 'attempts');
                    // Atualizar contador quando reconecta
                    if (typeof updateUnreadCount === 'function') {
                        updateUnreadCount();
                    }
                    // Re-subscribir aos canais após reconexão (será feito mais abaixo)
                });
                
                window.Echo.connector.socket.on('reconnect_attempt', () => {
                    console.log('LaravelEcho attempting to reconnect...');
                });
                
                window.Echo.connector.socket.on('reconnect_error', (error) => {
                    console.error('LaravelEcho reconnection error:', error);
                });
                
                window.Echo.connector.socket.on('error', (error) => {
                    console.error('LaravelEcho error:', error);
                });
            }

            const list = document.getElementById('notificationsList');
            
            // Variável para armazenar a subscription do canal
            let notificationChannel = null;

            // Função para atualizar o contador de mensagens não lidas usando AJAX
            function updateUnreadCount() {
                console.log('Atualizando contador de mensagens não lidas...');
                
                // Usar fetch (AJAX moderno) para buscar a contagem
                fetch('/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    const unreadCount = data.count || 0;
                    console.log('Contagem de não lidas:', unreadCount);
                    
                    // Atualizar contador na dashboard
                    const dashboardCount = document.getElementById('unreadMessagesCount');
                    if (dashboardCount) {
                        dashboardCount.textContent = unreadCount;
                        console.log('Contador da dashboard atualizado:', unreadCount);
                    }
                    
                    // Atualizar contador no sidebar
                    const sidebarCount = document.getElementById('sidebarUnreadCount');
                    if (sidebarCount) {
                        if (unreadCount > 0) {
                            sidebarCount.textContent = unreadCount;
                            sidebarCount.classList.remove('hidden');
                            console.log('Contador do sidebar atualizado:', unreadCount);
                        } else {
                            sidebarCount.classList.add('hidden');
                            console.log('Contador do sidebar ocultado (0 mensagens)');
                        }
                    } else {
                        // Se não existe, criar o elemento no sidebar se houver mensagens
                        const sidebarLink = document.querySelector('a[href="#"]:has(span:contains("Mensagens"))');
                        if (sidebarLink && unreadCount > 0) {
                            const existingBadge = sidebarLink.querySelector('.bg-red-100');
                            if (!existingBadge) {
                                const badge = document.createElement('span');
                                badge.id = 'sidebarUnreadCount';
                                badge.className = 'ml-auto bg-red-100 text-red-700 text-xs font-semibold px-1.5 py-0.5 rounded-full border border-red-200';
                                badge.textContent = unreadCount;
                                sidebarLink.appendChild(badge);
                            }
                        }
                    }
                })
                .catch(err => {
                    console.error('Erro ao atualizar contador via AJAX:', err);
                    // Tentar método alternativo se o endpoint específico falhar
                    fetch('/notifications')
                        .then(res => res.json())
                        .then(notifications => {
                            const unreadCount = notifications.filter(n => !n.is_seen).length;
                            const dashboardCount = document.getElementById('unreadMessagesCount');
                            if (dashboardCount) {
                                dashboardCount.textContent = unreadCount;
                            }
                            const sidebarCount = document.getElementById('sidebarUnreadCount');
                            if (sidebarCount) {
                                if (unreadCount > 0) {
                                    sidebarCount.textContent = unreadCount;
                                    sidebarCount.classList.remove('hidden');
                                } else {
                                    sidebarCount.classList.add('hidden');
                                }
                            }
                        })
                        .catch(err2 => console.error('Erro ao atualizar contador (método alternativo):', err2));
                });
            }
            
            // Expor função globalmente para poder ser chamada quando marcar como lida
            window.updateUnreadCount = updateUnreadCount;
            
            // Função para lidar com notificações recebidas
            function handleNotification(e) {
                console.log('🔔 Notificação recebida via LaravelEcho:', e);
                
                // Atualizar contador imediatamente via AJAX (não depende do WebSocket)
                updateUnreadCount();
                
                // Adicionar notificação à lista se o modal estiver aberto
                if (list) {
                    // Remover mensagem de "não há atividades"
                    const emptyMsg = list.querySelector('.text-center.text-sm.text-gray-500');
                    if (emptyMsg) emptyMsg.remove();

                    const el = document.createElement('div');
                    el.className = 'p-4 hover:bg-gray-50 transition-colors notification-item bg-blue-50';
                    el.setAttribute('data-id', e.id);
                    el.setAttribute('data-dynamic', 'true');
                    el.innerHTML = `
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-800">${e.titulo ?? 'Notificação'}</p>
                                <p class="text-xs text-gray-500 mt-1">${e.mensagem ?? ''}</p>
                                <p class="text-xs text-gray-400 mt-1">${e.created_at_human ?? e.created_at ?? ''}</p>
                            </div>
                            <span class="ml-2 h-2 w-2 bg-teal-400 rounded-full"></span>
                        </div>
                    `;
                    list.insertBefore(el, list.firstChild);
                }

                // Mostrar notificação do navegador se permitido
                if (Notification.permission === 'granted') {
                    const title = e.titulo ?? 'Notificação';
                    const options = { 
                        body: e.mensagem ?? '',
                        icon: '/favicon.ico',
                        badge: '/favicon.ico',
                        tag: 'notification-' + e.id,
                        data: {
                            url: '/dashboard',
                            id: e.id
                        }
                    };
                    try { 
                        const notification = new Notification(title, options);
                        notification.onclick = function() {
                            window.focus();
                            this.close();
                        };
                    } catch (err) { 
                        console.warn('Erro ao mostrar notificação:', err); 
                    }
                }

                // Recarregar notificações se o modal estiver aberto
                if (modal && modal.style.display === 'flex') {
                    if (typeof loadNotifications === 'function') {
                        loadNotifications();
                    }
                }
            }
            
            // Função para subscrever ao canal de notificações
            function subscribeToNotifications() {
                if (window.Echo && userId) {
                    // Cancelar subscription anterior se existir
                    if (notificationChannel) {
                        window.Echo.leave('user.' + userId);
                    }
                    
                    // Subscrever ao canal privado
                    notificationChannel = window.Echo.private('user.' + userId)
                        .listen('.NotificacaoCreated', handleNotification);
                    
                    console.log('✅ Subscrito ao canal user.' + userId);
                }
            }
            
            // Atualizar contador imediatamente quando a página carrega
            updateUnreadCount();
            
            // Atualizar contador periodicamente a cada 5 segundos (garantir atualização mesmo se LaravelEcho falhar)
            setInterval(() => {
                updateUnreadCount();
            }, 5000);

            // Subscrever ao canal privado
            subscribeToNotifications();
            
            // Re-subscribir quando reconectar
            window.Echo.connector.socket.on('reconnect', () => {
                console.log('🔄 Reconectado, re-subscribindo...');
                setTimeout(() => {
                    subscribeToNotifications();
                    updateUnreadCount();
                }, 500);
            });
        } else {
            console.warn('User não autenticado; skip Echo subscription');
        }
    } catch (err) {
        console.error('Echo init error', err);
    }

</script>
