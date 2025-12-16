# Exemplo de Criação de Notificações

Este documento mostra exemplos de como criar notificações no sistema.

## Exemplo 1: Criar Notificação Simples

```php
use App\Models\notificacoes;

// Criar uma notificação para um utilizador específico
notificacoes::create([
    'user_id' => 1, // ID do utilizador
    'morph_type' => 'evento', // Tipo do modelo relacionado
    'morph_id' => 5, // ID do modelo relacionado
    'titulo' => 'Nova Atividade',
    'mensagem' => 'Foi criada uma nova atividade que pode interessar-te!',
    'is_seen' => false, // Não lida
]);
```

## Exemplo 2: Criar Notificação para Múltiplos Utilizadores

```php
use App\Models\notificacoes;
use App\Models\User;

// Obter todos os utilizadores (exceto admins)
$users = User::where(function($query) {
    $query->whereNull('num_processo')
          ->orWhereRaw('UPPER(SUBSTRING(TRIM(num_processo), 1, 1)) != ?', ['A']);
})->get();

// Criar notificação para cada utilizador
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

## Exemplo 3: Criar Notificação no Controller (Já Implementado)

No `AtividadeController`, quando uma atividade é criada:

```php
// No método store()
$evento = evento::create([...]);

// Criar notificação
$this->criarNotificacaoAtividade($evento, $isAdmin);
```

## Exemplo 4: Criar Notificação Manualmente via Tinker

Para testar rapidamente:

```bash
docker-compose exec app php artisan tinker
```

No tinker:

```php
use App\Models\notificacoes;
use App\Models\User;

// Criar notificação para o utilizador com ID 1
$notificacao = notificacoes::create([
    'user_id' => 1,
    'morph_type' => 'evento',
    'morph_id' => 1,
    'titulo' => 'Teste de Notificação',
    'mensagem' => 'Esta é uma notificação de teste!',
    'is_seen' => false,
]);

// Verificar se foi criada
$notificacao->fresh();

// Ver notificações de um utilizador
$user = User::find(1);
$user->notificacoes;
```

## Exemplo 5: Criar Notificação quando um Convite é Enviado

No `ConviteController`, no método `store()`:

```php
// Após criar o convite
$convite = convite::create([...]);

// Criar notificação para o destinatário
notificacoes::create([
    'user_id' => $convite->for_user,
    'morph_type' => 'convite',
    'morph_id' => $convite->id,
    'titulo' => 'Novo Convite',
    'mensagem' => "Recebeste um convite para a atividade: {$evento->titulo}",
    'is_seen' => false,
]);
```

## Como Funciona

1. **Criação da Notificação**: Quando crias uma notificação usando `notificacoes::create()`, o modelo `notificacoes` dispara automaticamente o evento `NotificacaoCreated`.

2. **Broadcast Automático**: O evento `NotificacaoCreated` implementa `ShouldBroadcast`, então é automaticamente transmitido via Redis.

3. **LaravelEcho Server**: O LaravelEcho Server lê do Redis e transmite via Socket.IO para os clientes conectados.

4. **Cliente**: O JavaScript no `header.blade.php` escuta o canal privado `user.{userId}` e recebe a notificação em tempo real.

## Testando

1. **Criar uma atividade** através da interface web
2. **Verificar no console do navegador** (F12) se aparece "LaravelEcho connected successfully"
3. **Abrir o modal de notificações** clicando no ícone de notificações
4. **Verificar se a notificação aparece** em tempo real

## Estrutura da Notificação

- `user_id`: ID do utilizador que receberá a notificação
- `morph_type`: Tipo do modelo relacionado (ex: 'evento', 'convite')
- `morph_id`: ID do modelo relacionado
- `titulo`: Título da notificação
- `mensagem`: Mensagem da notificação
- `is_seen`: Se a notificação foi lida (false = não lida)

## Notas Importantes

- As notificações são criadas automaticamente quando uma atividade é criada/aprovada
- O evento é transmitido em tempo real via LaravelEcho
- Se o utilizador não estiver online, a notificação ficará disponível quando ele abrir o modal
- As notificações podem ser marcadas como lidas através da API `/notifications/{id}/read`

