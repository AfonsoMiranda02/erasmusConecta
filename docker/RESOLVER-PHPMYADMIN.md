# 🔧 Resolver Problema de Conexão do phpMyAdmin

Se o phpMyAdmin não está conseguindo conectar ao MySQL, siga estes passos:

## Solução Rápida

### 1. Parar os containers
```powershell
docker-compose down
```

### 2. Reiniciar os containers
```powershell
docker-compose up -d
```

### 3. Aguardar o MySQL estar pronto (30-60 segundos)
```powershell
# Verificar se o MySQL está saudável
docker-compose ps
```

### 4. Acessar o phpMyAdmin
- URL: http://localhost:8081
- **Servidor**: `db` (ou deixe em branco, já vem configurado)
- **Usuário**: `root`
- **Senha**: `root`

## Se ainda não funcionar

### Verificar se o MySQL está rodando
```powershell
docker-compose logs db
```

### Verificar se os containers estão na mesma rede
```powershell
docker network inspect erasmusconecta_erasmus_conecta_network
```

### Testar conexão manual
```powershell
docker-compose exec db mysql -u root -proot -e "SELECT 1"
```

### Reiniciar apenas o phpMyAdmin
```powershell
docker-compose restart phpmyadmin
```

## Configuração Manual no phpMyAdmin

Se precisar configurar manualmente ao acessar:

1. Acesse: http://localhost:8081
2. Clique em "Configuração do servidor"
3. Preencha:
   - **Servidor**: `db`
   - **Usuário**: `root`
   - **Senha**: `root`
   - **Porta**: `3306`

## Verificar Logs

Para ver o que está acontecendo:
```powershell
docker-compose logs phpmyadmin
docker-compose logs db
```

