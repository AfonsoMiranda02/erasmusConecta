# Configuração do Sistema de Web Push Notifications

Este documento explica como configurar o sistema de notificações webpush usando LaravelEcho.

## Pré-requisitos

1. LaravelEcho Server configurado e rodando na porta 6001
2. Socket.IO instalado e configurado
3. Service Worker habilitado no navegador

## Configuração das Chaves VAPID

As chaves VAPID (Voluntary Application Server Identification) são necessárias para Web Push Notifications.

### Gerar Chaves VAPID

Você pode gerar as chaves usando uma das seguintes opções:

1. **Usando um gerador online:**
   - Acesse: https://web-push-codelab.glitch.me/
   - Clique em "Generate VAPID Keys"
   - Copie as chaves geradas

2. **Usando Node.js:**
   ```bash
   npm install -g web-push
   web-push generate-vapid-keys
   ```

3. **Usando PHP (se tiver o pacote instalado):**
   ```bash
   composer require minishlink/web-push
   php artisan tinker
   ```
   No tinker:
   ```php
   use Minishlink\WebPush\VAPID;
   $keys = VAPID::createVapidKeys();
   print_r($keys);
   ```

### Configurar no .env

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
VAPID_PUBLIC_KEY=sua_chave_publica_aqui
VAPID_PRIVATE_KEY=sua_chave_privada_aqui
VAPID_SUBJECT=mailto:seu-email@exemplo.com
```

**Importante:** A `VAPID_SUBJECT` deve ser um email válido ou uma URL do seu domínio.

## Executar Migration

Execute a migration para criar a tabela de push subscriptions:

```bash
php artisan migrate
```

## Configuração do LaravelEcho Server

Certifique-se de que o LaravelEcho Server está configurado e rodando. O sistema espera que ele esteja disponível em:

```
http://localhost:6001
```

Para desenvolvimento local, você pode usar:

```bash
laravel-echo-server start
```

## Como Funciona

### 1. Subscrição de Push Notifications

Quando o utilizador clica no ícone de notificações pela primeira vez:
- O navegador solicita permissão para notificações
- O Service Worker é registrado
- Uma subscrição push é criada e enviada para o servidor
- A subscrição é armazenada na base de dados

### 2. Recebimento de Notificações

Quando uma nova notificação é criada:
- O evento `NotificacaoCreated` é disparado
- O LaravelEcho transmite a notificação via WebSocket para o canal privado do utilizador
- O cliente recebe a notificação em tempo real
- Uma notificação do navegador é exibida (se permitido)

### 3. Visualização de Notificações

- Clique no ícone de notificações para abrir o modal
- As notificações são exibidas em tempo real via LaravelEcho
- É possível marcar todas como lidas

## Estrutura de Arquivos

```
app/
├── Events/
│   └── NotificacaoCreated.php      # Evento para broadcast
├── Http/Controllers/
│   └── PushNotificationController.php  # Controller para push
└── Models/
    ├── PushSubscription.php        # Model para subscriptions
    └── notificacoes.php            # Model de notificações (atualizado)

config/
└── webpush.php                     # Configuração VAPID

database/migrations/
└── *_create_push_subscriptions_table.php

public/
└── service-worker.js               # Service Worker para push

resources/views/layouts/
└── header.blade.php                # Interface de notificações
```

## Rotas Disponíveis

- `POST /push/subscribe` - Subscrever para push notifications
- `POST /push/unsubscribe` - Cancelar subscrição
- `GET /notifications` - Obter lista de notificações
- `POST /notifications/{id}/read` - Marcar notificação como lida
- `POST /notifications/read-all` - Marcar todas como lidas

## Testando o Sistema

1. Certifique-se de que todas as configurações estão corretas
2. Acesse a aplicação no navegador
3. Clique no ícone de notificações no header
4. Permita as notificações quando solicitado
5. Crie uma notificação de teste no sistema
6. Verifique se a notificação aparece em tempo real

## Troubleshooting

### Notificações não aparecem

1. Verifique se o LaravelEcho Server está rodando
2. Verifique se as chaves VAPID estão configuradas corretamente
3. Verifique os logs do navegador (F12) para erros
4. Verifique se o Service Worker está registrado corretamente

### Erro ao subscrever

1. Verifique se as chaves VAPID estão corretas no `.env`
2. Verifique se a migration foi executada
3. Verifique os logs do Laravel

### LaravelEcho não conecta

1. Verifique se o servidor está rodando na porta 6001
2. Verifique a configuração do CORS
3. Verifique se o Socket.IO está instalado corretamente

## Notas Importantes

- O Service Worker só funciona em HTTPS (exceto localhost)
- As notificações do navegador requerem permissão do utilizador
- O LaravelEcho Server deve estar rodando para notificações em tempo real
- As chaves VAPID devem ser mantidas em segredo (especialmente a privada)

