# 🚀 Como Usar Vite em Desenvolvimento

## Problema de CORS com Vite

Se você está vendo erros de CORS como:
- "Pedido de origem cruzada bloqueado"
- "http://[::1]:5173/@vite/client"

## Solução Rápida

### Opção 1: Usar Build de Produção (Recomendado para testar)

```powershell
# Compilar assets uma vez
docker-compose exec node npm run build

# Recarregar a página
```

### Opção 2: Rodar Vite em Modo Desenvolvimento

```powershell
# 1. Certifique-se que o APP_URL está correto
docker-compose exec app php -r "`$env = file_get_contents('.env'); `$env = preg_replace('/APP_URL=.*/', 'APP_URL=http://localhost:8080', `$env); file_put_contents('.env', `$env);"

# 2. Limpar cache
docker-compose exec app php artisan config:clear

# 3. Iniciar Vite em modo desenvolvimento
docker-compose exec -d node npm run dev
```

### Opção 3: Rodar Vite Manualmente (Melhor para desenvolvimento)

```powershell
# Em um terminal separado, execute:
docker-compose exec node npm run dev
```

Depois acesse: http://localhost:8080

## Verificar se está funcionando

1. Verifique se o Vite está rodando:
```powershell
docker-compose logs node
```

2. Verifique se os assets foram compilados:
```powershell
Test-Path public/build/manifest.json
```

## Para Produção

Sempre use o build:
```powershell
docker-compose exec node npm run build
```

