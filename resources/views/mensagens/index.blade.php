@extends('layouts.app')

@section('title', 'Mensagens')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-800">Mensagens</h1>
        <p class="mt-1 text-sm text-gray-500">Todas as suas notificações</p>
    </div>

    <!-- Stats Card -->
    <div class="bg-white border border-gray-200 rounded-lg p-5 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Mensagens por ler</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900" id="unreadMessagesCount">{{ $naoLidas }}</p>
            </div>
            <div class="flex items-center space-x-4">
                @if($naoLidas > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                            Marcar todas como lidas
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        @if($notificacoes->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($notificacoes as $notificacao)
                    <a href="{{ route('mensagens.show', $notificacao->id) }}" class="block p-4 hover:bg-gray-50 transition-colors notification-item {{ !$notificacao->is_seen ? 'bg-blue-50' : '' }}" data-id="{{ $notificacao->id }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">{{ $notificacao->titulo }}</p>
                                        <p class="mt-1 text-sm text-gray-600">{{ $notificacao->mensagem }}</p>
                                        <p class="mt-2 text-xs text-gray-400">
                                            {{ $notificacao->created_at->format('d/m/Y H:i') }} • 
                                            {{ $notificacao->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex items-center space-x-2">
                                        @if(!$notificacao->is_seen)
                                            <span class="h-2.5 w-2.5 bg-teal-400 rounded-full"></span>
                                        @endif
                                        @if(!$notificacao->is_seen)
                                            <form action="{{ route('notifications.read', $notificacao->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-teal-600 hover:text-teal-700 font-medium">
                                                    Marcar como lida
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $notificacoes->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma mensagem</h3>
                <p class="mt-1 text-sm text-gray-500">Você não tem notificações ainda.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atualizar contador quando uma notificação é marcada como lida
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        const form = item.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevenir navegação ao clicar no botão
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Remover indicador de não lida
                        const indicator = item.querySelector('.bg-teal-400');
                        if (indicator) indicator.remove();
                        
                        // Remover classe bg-blue-50
                        item.classList.remove('bg-blue-50');
                        
                        // Remover botão "Marcar como lida"
                        form.remove();
                        
                        // Atualizar contador
                        const unreadCount = document.getElementById('unreadMessagesCount');
                        if (unreadCount) {
                            const currentCount = parseInt(unreadCount.textContent) || 0;
                            unreadCount.textContent = Math.max(0, currentCount - 1);
                        }
                        
                        // Atualizar contador global se a função existir
                        if (typeof window.updateUnreadCount === 'function') {
                            window.updateUnreadCount();
                        }
                    }
                })
                .catch(err => {
                    console.error('Erro ao marcar como lida:', err);
                });
            });
        }
    });
    
    // Atualizar contador quando "Marcar todas como lidas" é clicado
    const markAllForm = document.querySelector('form[action="{{ route('notifications.read-all') }}"]');
    if (markAllForm) {
        markAllForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(markAllForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Remover todos os indicadores
                    document.querySelectorAll('.bg-teal-400').forEach(indicator => indicator.remove());
                    document.querySelectorAll('.bg-blue-50').forEach(item => item.classList.remove('bg-blue-50'));
                    document.querySelectorAll('form[action*="/notifications/"]').forEach(form => {
                        if (form.action.includes('/read')) {
                            form.remove();
                        }
                    });
                    
                    // Atualizar contador
                    const unreadCount = document.getElementById('unreadMessagesCount');
                    if (unreadCount) {
                        unreadCount.textContent = '0';
                    }
                    
                    // Atualizar contador global se a função existir
                    if (typeof window.updateUnreadCount === 'function') {
                        window.updateUnreadCount();
                    }
                }
            })
            .catch(err => {
                console.error('Erro ao marcar todas como lidas:', err);
            });
        });
    }
});
</script>
@endsection
