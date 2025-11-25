# 🔧 Resolver Problema de Conexão MySQL

O MySQL estava com erro de configuração. Já foi corrigido! Siga estes passos:

## Solução

### 1. Parar todos os containers
```powershell
docker-compose down
```

### 2. Remover o volume do banco (para começar do zero)
```powershell
docker-compose down -v
```

**⚠️ ATENÇÃO:** Isso vai apagar todos os dados do banco! Se você já tem dados importantes, pule este passo.

### 3. Reiniciar os containers
```powershell
docker-compose up -d
```

### 4. Aguardar o MySQL inicializar (30-60 segundos)
```powershell
# Verificar se está rodando corretamente
docker-compose ps
```

Você deve ver o container `erasmus_conecta_db` com status "Up" (não mais "Restarting").

### 5. Verificar os logs do MySQL
```powershell
docker-compose logs db
```

Deve mostrar mensagens de sucesso, não mais erros sobre MYSQL_USER.

### 6. Executar as migrações
```powershell
docker-compose exec app php artisan migrate
```

## Verificar Conexão

### Testar conexão manual
```powershell
docker-compose exec db mysql -uroot -proot -e "SELECT 1"
```

Se retornar "1", está funcionando!

### Verificar no Laravel
Acesse: http://localhost:8080

O erro de conexão deve ter desaparecido.

## Se ainda não funcionar

### Verificar arquivo .env
Certifique-se de que o `.env` tem:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=erasmus_conecta
DB_USERNAME=root
DB_PASSWORD=root
```

### Limpar cache do Laravel
```powershell
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

### Verificar se os containers estão na mesma rede
```powershell
docker network inspect erasmusconecta_erasmus_conecta_network
```

