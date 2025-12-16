# LaravelEcho Server - Configuração e Uso

Este documento explica como configurar e usar o LaravelEcho Server para notificações em tempo real.

## Configuração Automática (Docker)

O LaravelEcho Server já está configurado no `docker-compose.yml` como um serviço separado.

### Iniciar o Serviço

Para iniciar o LaravelEcho Server junto com os outros serviços:

```bash
docker-compose up -d echo
```

Ou para iniciar todos os serviços:

```bash
docker-compose up -d
```

### Verificar se está rodando

```bash
docker-compose ps echo
```

### Ver logs

```bash
docker-compose logs -f echo
```

### Parar o serviço

```bash
docker-compose stop echo
```

## Configuração Manual (Fora do Docker)

Se preferir rodar o LaravelEcho Server manualmente:

### 1. Instalar globalmente

```bash
npm install -g laravel-echo-server
```

### 2. Inicializar configuração

```bash
laravel-echo-server init
```

### 3. Editar configuração

O arquivo `laravel-echo-server.json` já está criado com as configurações corretas:

- **Host**: 0.0.0.0 (aceita conexões de qualquer IP)
- **Port**: 6001
- **Database**: Redis (conecta ao Redis do Docker)
- **Auth Endpoint**: `/broadcasting/auth`

### 4. Iniciar o servidor

```bash
laravel-echo-server start
```

## Configuração do Redis

O LaravelEcho Server está configurado para usar o Redis do Docker. A configuração está em `laravel-echo-server.json`:

```json
{
    "database": "redis",
    "databaseConfig": {
        "redis": {
            "host": "redis",
            "port": "6379"
        }
    }
}
```

**Nota**: Se estiver rodando fora do Docker, altere `"host": "redis"` para `"host": "localhost"`.

## Autenticação de Canais Privados

O LaravelEcho Server autentica canais privados através do endpoint `/broadcasting/auth`. Este endpoint já está configurado no Laravel através do arquivo `routes/channels.php`.

### Canais Configurados

- `user.{userId}` - Canal privado para notificações do utilizador
- `App.Models.User.{id}` - Canal padrão do Laravel

## Troubleshooting

### Erro: "Failed to connect to WebSocket"

1. Verifique se o LaravelEcho Server está rodando:
   ```bash
   docker-compose ps echo
   ```

2. Verifique se a porta 6001 está acessível:
   ```bash
   curl http://localhost:6001
   ```

3. Verifique os logs:
   ```bash
   docker-compose logs echo
   ```

### Erro: "Authentication failed"

1. Verifique se o endpoint `/broadcasting/auth` está acessível
2. Verifique se o CSRF token está sendo enviado corretamente
3. Verifique os logs do Laravel para erros de autenticação

### Erro: "Redis connection failed"

1. Verifique se o Redis está rodando:
   ```bash
   docker-compose ps redis
   ```

2. Verifique se o host do Redis está correto no `laravel-echo-server.json`

### Porta 6001 já em uso

Se a porta 6001 já estiver em uso:

1. Pare o processo que está usando a porta
2. Ou altere a porta no `laravel-echo-server.json` e no `header.blade.php`

## Testando a Conexão

1. Abra o navegador e vá para a aplicação
2. Abra o console do navegador (F12)
3. Você deve ver: "LaravelEcho connected successfully"
4. Se houver erros, verifique os logs do LaravelEcho Server

## Produção

Para produção, considere:

1. Usar HTTPS/WSS
2. Configurar SSL no LaravelEcho Server
3. Usar um processo manager como PM2
4. Configurar logs adequados
5. Configurar CORS corretamente para o seu domínio

## Referências

- [LaravelEcho Server GitHub](https://github.com/tlaverdure/laravel-echo-server)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)

