# LaravelEcho Server - Informações sobre localhost:6001

## O que é o localhost:6001?

O `localhost:6001` é o **LaravelEcho Server**, um servidor WebSocket que transmite eventos em tempo real do Laravel para os clientes (navegadores).

## O que aparece quando acessa localhost:6001?

Quando você acessa `http://localhost:6001/` no navegador, você verá apenas:

```
OK
```

Isso é **normal**! O LaravelEcho Server não tem uma interface web visual. É apenas um servidor WebSocket que:

1. **Escuta conexões WebSocket** na porta 6001
2. **Lê eventos do Redis** (quando o Laravel faz broadcast)
3. **Transmite eventos via Socket.IO** para os clientes conectados

## Como verificar se está funcionando?

### 1. Verificar se o servidor está rodando

```bash
docker-compose ps echo
```

Deve mostrar algo como:
```
NAME                    STATUS
erasmus_conecta_echo    Up X minutes
```

### 2. Verificar os logs

```bash
docker-compose logs -f echo
```

Você verá logs como:
- `Server listening on port 6001`
- `New client connected`
- `Client disconnected`
- `Channel subscribed: private-user.1`

### 3. Testar a conexão HTTP

```bash
curl http://localhost:6001/
```

Resposta esperada: `OK`

### 4. Verificar no navegador

1. Abra o console do navegador (F12)
2. Vá para a aplicação (ex: `http://localhost`)
3. Você deve ver no console:
   ```
   Connecting to LaravelEcho Server at: http://localhost:6001
   LaravelEcho connected successfully
   ```

## O que o LaravelEcho Server faz?

```
┌─────────────┐
│   Laravel   │
│  (Backend)  │
└──────┬──────┘
       │
       │ Broadcast Event
       │ (via Redis)
       ▼
┌─────────────┐
│    Redis    │
│  (Storage)  │
└──────┬──────┘
       │
       │ LaravelEcho Server
       │ lê do Redis
       ▼
┌─────────────┐      ┌──────────────┐
│ LaravelEcho │─────►│  Socket.IO   │
│   Server    │      │  (WebSocket) │
│ :6001       │      └──────┬───────┘
└─────────────┘             │
                            │ Eventos em
                            │ Tempo Real
                            ▼
                    ┌─────────────┐
                    │   Browser   │
                    │ (JavaScript)│
                    └─────────────┘
```

## Não há interface web

O LaravelEcho Server **não tem dashboard ou interface web**. É apenas um servidor de backend que:

- Aceita conexões WebSocket
- Autentica canais privados
- Transmite eventos em tempo real

## Como monitorar?

### Opção 1: Logs do Docker

```bash
docker-compose logs -f echo
```

### Opção 2: Console do Navegador

Abra o console (F12) e veja:
- Mensagens de conexão/desconexão
- Eventos recebidos
- Erros de conexão

### Opção 3: Redis CLI (ver eventos)

```bash
docker-compose exec redis redis-cli
> MONITOR
```

Isso mostra todos os comandos Redis, incluindo eventos de broadcast.

## Resumo

- **localhost:6001** = LaravelEcho Server (WebSocket)
- **Resposta "OK"** = Servidor está funcionando
- **Não há interface web** = É apenas um servidor backend
- **Para monitorar** = Use logs do Docker ou console do navegador

## Troubleshooting

### Se não conseguir conectar:

1. Verifique se o container está rodando:
   ```bash
   docker-compose ps echo
   ```

2. Verifique os logs:
   ```bash
   docker-compose logs echo
   ```

3. Verifique se a porta está acessível:
   ```bash
   curl http://localhost:6001/
   ```

4. Verifique se o Redis está funcionando:
   ```bash
   docker-compose ps redis
   ```

5. Verifique no console do navegador se há erros de conexão WebSocket


