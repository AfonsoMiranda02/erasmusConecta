# Sistema de Notificações WebPush com LaravelEcho

Este documento explica detalhadamente todo o sistema de notificações em tempo real implementado no projeto ErasmusConecta.

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Arquitetura do Sistema](#arquitetura-do-sistema)
3. [Componentes Implementados](#componentes-implementados)
4. [Configuração](#configuração)
5. [Como Funciona](#como-funciona)
6. [Fluxo de Dados](#fluxo-de-dados)
7. [Troubleshooting](#troubleshooting)

---

## 🎯 Visão Geral

O sistema implementado permite:
- **Notificações em tempo real** via LaravelEcho e Socket.IO
- **Web Push Notifications** para navegadores
- **Atualização automática** de contadores de mensagens não lidas
- **Sincronização** entre diferentes abas do navegador
- **Reconexão automática** em caso de desconexão

---

## 🏗️ Arquitetura do Sistema

```
┌─────────────┐
│   Laravel   │
│  (Backend)  │
└──────┬──────┘
       │
       │ Cria Notificação
       │
       ▼
┌─────────────┐      ┌──────────────┐      ┌─────────────┐
│   Redis     │◄─────│ LaravelEcho  │─────►│  Socket.IO  │
│ (Broadcast) │      │    Server    │      │  (WebSocket)│
└─────────────┘      └──────────────┘      └──────┬──────┘
                                                   │
                                                   │ Eventos em Tempo Real
                                                   │
                                                   ▼
                                            ┌─────────────┐
                                            │   Browser   │
                                            │  (Frontend) │
                                            └─────────────┘
```

### Componentes Principais

1. **Laravel Backend**: Cria notificações e faz broadcast via Redis
2. **LaravelEcho Server**: Lê do Redis e transmite via Socket.IO
3. **Redis**: Armazena eventos de broadcast
4. **Socket.IO**: WebSocket para comunicação em tempo real
5. **Service Worker**: Gerencia push notifications do navegador
6. **JavaScript Frontend**: Escuta eventos e atualiza a UI

---

## 📦 Componentes Implementados

### 1. Service Worker (`public/service-worker.js`)

O Service Worker gerencia as notificações push do navegador.

**Funcionalidades:**
- Intercepta eventos de push
- Exibe notificações do navegador
- Gerencia cliques nas notificações
- Abre a aplicação quando o utilizador clica

**Eventos tratados:**
- `install`: Instalação do Service Worker
- `activate`: Ativação e limpeza de cache
- `push`: Recebe notificações push
- `notificationclick`: Clique em notificações
- `message`: Mensagens do cliente

### 2. Controller de Push Notifications (`app/Http/Controllers/PushNotificationController.php`)

Gerencia subscrições e notificações.

**Métodos:**
- `subscribe()`: Subscreve utilizador para push notifications
- `unsubscribe()`: Cancela subscrição
- `getNotifications()`: Retorna lista de notificações
- `getUnreadCount()`: Retorna contagem de não lidas (AJAX)
- `markAsRead()`: Marca notificação como lida
- `markAllAsRead()`: Marca todas como lidas

### 3. Event de Broadcast (`app/Events/NotificacaoCreated.php`)

Evento que é disparado quando uma notificação é criada.

**Características:**
- Implementa `ShouldBroadcastNow` (broadcast imediato, sem fila)
- Transmite para canal privado `user.{userId}`
- Inclui dados da notificação no broadcast

**Dados transmitidos:**
- `id`: ID da notificação
- `titulo`: Título da notificação
- `mensagem`: Mensagem da notificação
- `is_seen`: Se foi lida
- `created_at`: Data de criação (ISO)
- `created_at_human`: Data formatada (ex: "há 2 minutos")

### 4. Model de Notificações (`app/Models/notificacoes.php`)

Model atualizado para disparar eventos automaticamente.

**Mudanças:**
- Adicionado `$dispatchesEvents` para disparar `NotificacaoCreated` quando criada
- Relação com `User` e `morphable` (polimórfica)

### 5. Model de Push Subscriptions (`app/Models/PushSubscription.php`)

Armazena subscrições de push notifications dos utilizadores.

**Campos:**
- `user_id`: ID do utilizador
- `endpoint`: URL do endpoint de push
- `public_key`: Chave pública VAPID
- `auth_token`: Token de autenticação

### 6. Migration (`database/migrations/..._create_push_subscriptions_table.php`)

Cria tabela para armazenar subscrições push.

**Estrutura:**
- `id`: Primary key
- `user_id`: Foreign key para users
- `endpoint`: URL única do endpoint
- `public_key`: Chave pública
- `auth_token`: Token de autenticação
- `timestamps`: created_at, updated_at

### 7. Configuração WebPush (`config/webpush.php`)

Configuração para chaves VAPID.

**Variáveis de ambiente necessárias:**
- `VAPID_PUBLIC_KEY`: Chave pública VAPID
- `VAPID_PRIVATE_KEY`: Chave privada VAPID
- `VAPID_SUBJECT`: Email ou URL do domínio

### 8. Configuração Broadcasting (`config/broadcasting.php`)

Configurado para usar Redis como broadcaster.

**Configuração:**
- `BROADCAST_CONNECTION=redis` no `.env`
- Conexão Redis configurada para broadcast

### 9. LaravelEcho Server (`laravel-echo-server.json`)

Configuração do servidor LaravelEcho.

**Configurações principais:**
- `host`: 0.0.0.0 (aceita conexões de qualquer IP)
- `port`: 6001
- `database`: redis
- `authHost`: http://nginx (para autenticação)
- `authEndpoint`: /broadcasting/auth
- `devMode`: true (desenvolvimento)

### 10. Docker Compose (`docker-compose.yml`)

Serviço `echo` adicionado para rodar LaravelEcho Server.

**Configuração:**
- Imagem: `node:20-alpine`
- Porta: `6001:6001`
- Comando: `laravel-echo-server start --force`
- Dependências: redis, nginx

### 11. Rotas (`routes/web.php`)

Rotas adicionadas para o sistema de notificações:

```php
POST   /push/subscribe              // Subscrever push
POST   /push/unsubscribe            // Cancelar subscrição
GET    /notifications                // Listar notificações
GET    /notifications/unread-count   // Contagem de não lidas (AJAX)
POST   /notifications/{id}/read     // Marcar como lida
POST   /notifications/read-all      // Marcar todas como lidas
```

### 12. Canais de Broadcast (`routes/channels.php`)

Canais privados configurados:

```php
// Canal padrão do Laravel
Broadcast::channel('App.Models.User.{id}', ...);

// Canal para notificações do utilizador
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
```

### 13. JavaScript no Header (`resources/views/layouts/header.blade.php`)

Implementação completa do frontend.

**Funcionalidades principais:**

#### a) Inicialização do LaravelEcho
- Detecta host do servidor (localhost:6001 em desenvolvimento)
- Configura Socket.IO com reconexão automática
- Trata eventos de conexão/desconexão

#### b) Função `updateUnreadCount()`
- Usa `fetch()` (AJAX) para buscar contagem
- Endpoint: `/notifications/unread-count`
- Atualiza elementos:
  - `#unreadMessagesCount` (dashboard)
  - `#sidebarUnreadCount` (sidebar)
- Executa a cada 5 segundos como fallback

#### c) Função `handleNotification(e)`
- Processa notificações recebidas via LaravelEcho
- Atualiza contador via AJAX
- Adiciona notificação ao modal se aberto
- Mostra notificação do navegador
- Recarrega lista se modal estiver aberto

#### d) Função `subscribeToNotifications()`
- Gerencia subscrição ao canal privado
- Re-subscrita após reconexão
- Cancela subscrição anterior antes de criar nova

#### e) Service Worker Registration
- Registra service worker para push notifications
- Solicita permissão do utilizador
- Cria subscrição push
- Envia subscrição para o servidor

### 14. Integração no AtividadeController (`app/Http/Controllers/AtividadeController.php`)

Notificações automáticas quando atividades são criadas/aprovadas.

**Métodos adicionados:**
- `criarNotificacaoAtividade()`: Cria notificação ao criar atividade
- `criarNotificacaoAprovacao()`: Cria notificação ao aprovar atividade

**Lógica:**
- **Admin cria atividade pública**: Notifica todos os utilizadores (exceto admins)
- **Não-admin cria atividade**: Notifica apenas o criador
- **Atividade aprovada**: Notifica criador + todos (se pública)

### 15. Interface Visual

#### Dashboard (`resources/views/dashboard/index.blade.php`)
- Card "Mensagens por ler" com ID `unreadMessagesCount`
- Atualiza automaticamente via AJAX

#### Dashboard Admin (`resources/views/admin/dashboard.blade.php`)
- Card "Mensagens por ler" com ID `unreadMessagesCount`
- Controller atualizado para passar `$mensagensNaoLidas`

#### Sidebar (`resources/views/layouts/sidebar.blade.php`)
- Badge de contagem com ID `sidebarUnreadCount`
- Aparece/desaparece conforme contagem

#### Header (`resources/views/layouts/header.blade.php`)
- Modal de notificações
- Ícone de notificações
- Lista de notificações em tempo real

---

## ⚙️ Configuração

### 1. Variáveis de Ambiente

Adicione ao `.env`:

```env
# Broadcasting
BROADCAST_CONNECTION=redis

# Redis (já configurado no Docker)
REDIS_HOST=redis
REDIS_PORT=6379

# VAPID Keys (para Web Push)
VAPID_PUBLIC_KEY=sua_chave_publica_aqui
VAPID_PRIVATE_KEY=sua_chave_privada_aqui
VAPID_SUBJECT=mailto:seu-email@exemplo.com
```

### 2. Gerar Chaves VAPID

```bash
# Opção 1: Online
# Acesse: https://web-push-codelab.glitch.me/

# Opção 2: Node.js
npm install -g web-push
web-push generate-vapid-keys

# Opção 3: PHP
composer require minishlink/web-push
php artisan tinker
# use Minishlink\WebPush\VAPID;
# $keys = VAPID::createVapidKeys();
```

### 3. Executar Migrations

```bash
docker-compose exec app php artisan migrate
```

Isso cria:
- Tabela `push_subscriptions`
- Tabela `jobs` (para queue de broadcast)

### 4. Iniciar Serviços

```bash
# Iniciar todos os serviços
docker-compose up -d

# Verificar LaravelEcho Server
docker-compose ps echo
docker-compose logs -f echo
```

---

## 🔄 Como Funciona

### Fluxo Completo de uma Notificação

1. **Criação da Notificação** (ex: criar atividade)
   ```
   AtividadeController::store()
   → evento::create()
   → criarNotificacaoAtividade()
   → notificacoes::create()
   ```

2. **Disparo do Evento**
   ```
   notificacoes::create()
   → Model dispara evento 'created'
   → NotificacaoCreated::__construct()
   → Laravel faz broadcast via Redis
   ```

3. **Transmissão via LaravelEcho**
   ```
   Redis recebe evento
   → LaravelEcho Server lê do Redis
   → Transmite via Socket.IO
   → Cliente recebe no canal privado 'user.{userId}'
   ```

4. **Processamento no Frontend**
   ```
   JavaScript recebe evento
   → handleNotification() é chamada
   → updateUnreadCount() atualiza contador via AJAX
   → Notificação adicionada ao modal (se aberto)
   → Notificação do navegador exibida (se permitido)
   ```

### Atualização do Contador

O contador "Mensagens por ler" é atualizado:

1. **Via LaravelEcho** (tempo real):
   - Quando uma notificação é recebida
   - Função `updateUnreadCount()` é chamada
   - Faz requisição AJAX para `/notifications/unread-count`
   - Atualiza elementos na página

2. **Via Polling** (fallback):
   - A cada 5 segundos
   - Garante atualização mesmo se LaravelEcho falhar

3. **Ao marcar como lida**:
   - Após marcar, `updateUnreadCount()` é chamada
   - Contador é atualizado imediatamente

### Reconexão Automática

Se o LaravelEcho desconectar:

1. Socket.IO tenta reconectar automaticamente
2. Após reconexão, `subscribeToNotifications()` é chamada
3. Re-subscrição ao canal privado
4. Contador é atualizado

---

## 📊 Fluxo de Dados

### 1. Criação de Notificação

```
┌─────────────────┐
│ AtividadeController│
│   store()        │
└────────┬─────────┘
         │
         ▼
┌─────────────────┐
│ criarNotificacao│
│  Atividade()    │
└────────┬─────────┘
         │
         ▼
┌─────────────────┐      ┌──────────────┐
│ notificacoes    │─────►│ Notificacao   │
│  ::create()     │      │   Created     │
└─────────────────┘      └──────┬───────┘
                                 │
                                 ▼
                          ┌──────────────┐
                          │    Redis     │
                          │  (Broadcast) │
                          └──────────────┘
```

### 2. Transmissão em Tempo Real

```
┌──────────────┐      ┌──────────────┐      ┌──────────────┐
│    Redis     │─────►│ LaravelEcho  │─────►│  Socket.IO   │
│  (Broadcast) │      │    Server    │      │  (WebSocket) │
└──────────────┘      └──────────────┘      └──────┬───────┘
                                                    │
                                                    ▼
                                            ┌──────────────┐
                                            │   Browser    │
                                            │  JavaScript  │
                                            └──────────────┘
```

### 3. Atualização da UI

```
┌──────────────┐
│ handleNotif()│
└──────┬───────┘
       │
       ▼
┌──────────────┐      ┌──────────────┐      ┌──────────────┐
│ updateUnread │─────►│   AJAX       │─────►│  Controller  │
│    Count()   │      │   Request    │      │ getUnread    │
└──────┬───────┘      └──────────────┘      │   Count()    │
       │                                    └──────┬───────┘
       │                                           │
       │                                           ▼
       │                                    ┌──────────────┐
       │                                    │   Database   │
       │                                    │   (Count)    │
       │                                    └──────┬───────┘
       │                                           │
       │                                           ▼
       │                                    ┌──────────────┐
       │                                    │  JSON Response│
       │                                    │  {count: X}  │
       │                                    └──────┬───────┘
       │                                           │
       └───────────────────────────────────────────┘
                       │
                       ▼
            ┌──────────────────────┐
            │  Atualiza Elementos  │
            │  - Dashboard Count   │
            │  - Sidebar Badge     │
            │  - Modal List        │
            └──────────────────────┘
```

---

## 🔧 Troubleshooting

### Problema: LaravelEcho desconecta constantemente

**Solução:**
- Verifique se o servidor está rodando: `docker-compose ps echo`
- Verifique logs: `docker-compose logs -f echo`
- O código já tem reconexão automática configurada
- Verifique se a porta 6001 está acessível

### Problema: Contador não atualiza

**Solução:**
1. Abra o console do navegador (F12)
2. Verifique se há erros de AJAX
3. Verifique se o endpoint `/notifications/unread-count` está acessível
4. Verifique se os elementos com IDs existem na página:
   - `#unreadMessagesCount`
   - `#sidebarUnreadCount`

### Problema: Notificações não aparecem em tempo real

**Solução:**
1. Verifique se o LaravelEcho está conectado (console)
2. Verifique se o evento está sendo transmitido (logs do LaravelEcho)
3. Verifique se o canal está correto: `user.{userId}`
4. Verifique se a autenticação está funcionando (`/broadcasting/auth`)

### Problema: Erro "Table 'jobs' doesn't exist"

**Solução:**
```bash
docker-compose exec app php artisan queue:table
docker-compose exec app php artisan migrate
```

### Problema: Erro "Class Pusher\Pusher not found"

**Solução:**
- Já corrigido: `BROADCAST_CONNECTION=redis` no `.env`
- Evento usa `ShouldBroadcastNow` (não precisa de queue)

### Problema: Service Worker não registra

**Solução:**
1. Service Worker só funciona em HTTPS (exceto localhost)
2. Verifique se o arquivo existe: `public/service-worker.js`
3. Verifique permissões do navegador para notificações

---

## 📝 Exemplos de Uso

### Criar Notificação Manualmente

```php
use App\Models\notificacoes;

notificacoes::create([
    'user_id' => 1,
    'morph_type' => 'evento',
    'morph_id' => 5,
    'titulo' => 'Nova Atividade',
    'mensagem' => 'Foi criada uma nova atividade!',
    'is_seen' => false,
]);
```

### Criar Notificação para Múltiplos Utilizadores

```php
$users = User::where('id', '!=', $evento->created_by)
    ->get()
    ->filter(function($user) {
        $primeiroChar = !empty($user->num_processo) 
            ? strtoupper(trim($user->num_processo)[0]) 
            : '';
        return $primeiroChar !== 'A'; // Excluir admins
    });

foreach ($users as $user) {
    notificacoes::create([
        'user_id' => $user->id,
        'morph_type' => 'evento',
        'morph_id' => $evento->id,
        'titulo' => 'Nova Atividade Disponível',
        'mensagem' => "Foi criada uma nova atividade: {$evento->titulo}",
        'is_seen' => false,
    ]);
}
```

### Testar via Tinker

```bash
docker-compose exec app php artisan tinker
```

```php
use App\Models\notificacoes;
use App\Models\User;

$user = User::first();
$notificacao = notificacoes::create([
    'user_id' => $user->id,
    'morph_type' => 'evento',
    'morph_id' => 1,
    'titulo' => 'Teste',
    'mensagem' => 'Esta é uma notificação de teste!',
    'is_seen' => false,
]);
```

---

## 🎯 Funcionalidades Implementadas

✅ **Notificações em Tempo Real**
- Broadcast via LaravelEcho
- Transmissão via Socket.IO
- Recepção no frontend

✅ **Web Push Notifications**
- Service Worker configurado
- Subscrição de push
- Notificações do navegador

✅ **Atualização Automática de Contadores**
- Via AJAX (fetch)
- Atualização em tempo real
- Polling como fallback (5 segundos)

✅ **Reconexão Automática**
- Socket.IO com reconexão
- Re-subscrição automática
- Tratamento de erros

✅ **Interface Completa**
- Modal de notificações
- Contador na dashboard
- Contador no sidebar
- Lista de notificações

✅ **Integração com Atividades**
- Notificações ao criar atividade
- Notificações ao aprovar atividade
- Notificações para utilizadores relevantes

---

## 📚 Arquivos Criados/Modificados

### Arquivos Criados:
1. `public/service-worker.js` - Service Worker para push
2. `app/Http/Controllers/PushNotificationController.php` - Controller de notificações
3. `app/Events/NotificacaoCreated.php` - Evento de broadcast
4. `app/Models/PushSubscription.php` - Model de subscrições
5. `config/webpush.php` - Configuração VAPID
6. `laravel-echo-server.json` - Configuração LaravelEcho
7. `database/migrations/..._create_push_subscriptions_table.php` - Migration
8. `database/migrations/..._create_jobs_table.php` - Migration (queue)

### Arquivos Modificados:
1. `app/Models/notificacoes.php` - Adicionado dispatchesEvents
2. `app/Models/User.php` - Adicionado relação pushSubscriptions
3. `app/Http/Controllers/AtividadeController.php` - Notificações automáticas
4. `app/Http/Controllers/Admin/AdminDashboardController.php` - Contador de mensagens
5. `routes/web.php` - Rotas de notificações
6. `routes/channels.php` - Canais privados
7. `config/broadcasting.php` - Configuração Redis
8. `docker-compose.yml` - Serviço echo
9. `resources/views/layouts/header.blade.php` - JavaScript completo
10. `resources/views/dashboard/index.blade.php` - ID no contador
11. `resources/views/admin/dashboard.blade.php` - Card de mensagens
12. `resources/views/layouts/sidebar.blade.php` - ID no badge

---

## 🚀 Próximos Passos (Opcional)

1. **Melhorias de Performance**
   - Cache de contagem de notificações
   - Paginação de notificações
   - Lazy loading

2. **Funcionalidades Adicionais**
   - Filtros de notificações
   - Agrupamento por tipo
   - Notificações por email (opcional)

3. **Segurança**
   - Rate limiting nas rotas
   - Validação de subscrições
   - Sanitização de dados

4. **Testes**
   - Testes unitários para eventos
   - Testes de integração para broadcast
   - Testes E2E para notificações

---

## 📖 Referências

- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- [LaravelEcho Server](https://github.com/tlaverdure/laravel-echo-server)
- [Socket.IO](https://socket.io/)
- [Web Push API](https://developer.mozilla.org/en-US/docs/Web/API/Push_API)
- [Service Workers](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API)

---

## ✅ Checklist de Verificação

- [x] Service Worker criado e funcionando
- [x] LaravelEcho Server configurado e rodando
- [x] Redis configurado para broadcast
- [x] Eventos de broadcast implementados
- [x] Rotas de notificações criadas
- [x] JavaScript de atualização automática
- [x] Contadores atualizando em tempo real
- [x] Reconexão automática configurada
- [x] Notificações automáticas ao criar atividades
- [x] Interface visual completa
- [x] Documentação criada

---

**Última atualização:** Dezembro 2024
**Versão:** 1.0

