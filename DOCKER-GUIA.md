# 🐳 Guia Completo - Como Rodar o Projeto no Docker

Este guia vai te ensinar passo a passo como rodar o projeto ErasmusConecta usando Docker.

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

1. **Docker Desktop** - [Download aqui](https://www.docker.com/products/docker-desktop/)
2. **Git** (opcional, se ainda não tiver o projeto)

## 🚀 Passo a Passo - Primeira Vez

### 1️⃣ Verificar se o Docker está rodando

Abra o **Docker Desktop** e verifique se está rodando (ícone verde no canto inferior direito).

### 2️⃣ Abrir o Terminal/PowerShell

- **Windows**: Pressione `Win + X` e escolha "Windows PowerShell" ou "Terminal"
- Navegue até a pasta do projeto:
  ```powershell
  cd C:\wamp64\www\erasmusConecta
  ```

### 3️⃣ Copiar o arquivo de ambiente

```powershell
Copy-Item env.docker.example .env
```

### 4️⃣ Iniciar os containers Docker

```powershell
docker-compose up -d
```

Este comando vai:
- Baixar as imagens necessárias (primeira vez pode demorar)
- Criar e iniciar todos os containers
- Configurar a rede entre os serviços

**⏳ Aguarde alguns minutos na primeira vez!**

### 5️⃣ Verificar se os containers estão rodando

```powershell
docker-compose ps
```

Você deve ver todos os containers com status "Up":
- erasmus_conecta_app
- erasmus_conecta_nginx
- erasmus_conecta_db
- erasmus_conecta_redis
- erasmus_conecta_node
- erasmus_conecta_phpmyadmin

### 6️⃣ Instalar dependências do Composer

```powershell
docker-compose exec app composer install
```

### 7️⃣ Instalar dependências do NPM

```powershell
docker-compose exec node npm install
```

### 8️⃣ Gerar a chave da aplicação Laravel

```powershell
docker-compose exec app php artisan key:generate
```

### 9️⃣ Executar as migrações do banco de dados

```powershell
docker-compose exec app php artisan migrate
```

### 🔟 Criar link simbólico do storage

```powershell
docker-compose exec app php artisan storage:link
```

### 1️⃣1️⃣ (Opcional) Popular o banco com dados de exemplo

```powershell
docker-compose exec app php artisan db:seed
```

## ✅ Pronto! Acesse a aplicação

Agora você pode acessar:

- 🌐 **Aplicação Laravel**: http://localhost:8080
- 🗄️ **phpMyAdmin**: http://localhost:8081
  - **Usuário**: `root`
  - **Senha**: `root`
  - **Servidor**: `db`

## 📝 Comandos Úteis do Dia a Dia

### Parar os containers
```powershell
docker-compose stop
```

### Iniciar os containers (depois de parar)
```powershell
docker-compose start
```

### Parar e remover os containers
```powershell
docker-compose down
```

### Ver os logs em tempo real
```powershell
docker-compose logs -f
```

### Ver logs de um serviço específico
```powershell
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Acessar o terminal do container PHP
```powershell
docker-compose exec app bash
```

### Executar comandos Artisan
```powershell
docker-compose exec app php artisan [comando]
```

Exemplos:
```powershell
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Executar comandos Composer
```powershell
docker-compose exec app composer [comando]
```

Exemplos:
```powershell
docker-compose exec app composer install
docker-compose exec app composer update
docker-compose exec app composer require [pacote]
```

### Executar comandos NPM
```powershell
docker-compose exec node npm [comando]
```

Exemplos:
```powershell
docker-compose exec node npm install
docker-compose exec node npm run dev
docker-compose exec node npm run build
```

## 🔧 Resolução de Problemas

### Problema: Porta já está em uso

Se der erro de porta ocupada, você pode alterar as portas no `docker-compose.yml`:

```yaml
nginx:
  ports:
    - "8080:80"  # Altere 8080 para outra porta (ex: 8082)

phpmyadmin:
  ports:
    - "8081:80"  # Altere 8081 para outra porta (ex: 8083)
```

### Problema: Containers não iniciam

1. Verifique se o Docker Desktop está rodando
2. Verifique os logs: `docker-compose logs`
3. Tente reconstruir: `docker-compose build --no-cache`

### Problema: Erro de permissão

```powershell
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Problema: Banco de dados não conecta

1. Verifique se o container `db` está rodando: `docker-compose ps`
2. Verifique o arquivo `.env` e confirme:
   ```
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=erasmus_conecta
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

### Limpar tudo e começar do zero

```powershell
# Parar e remover containers, volumes e redes
docker-compose down -v

# Reconstruir tudo
docker-compose build --no-cache
docker-compose up -d
```

## 🎯 Script Automático (Alternativa Rápida)

Se preferir, pode usar o script automático:

```powershell
.\docker\setup.ps1
```

Este script faz todos os passos automaticamente!

## 📚 Estrutura dos Serviços

- **app**: Container PHP 8.2 com Laravel
- **nginx**: Servidor web que serve a aplicação
- **db**: Banco de dados MySQL 8.0
- **redis**: Cache e sessões
- **node**: Node.js para compilar assets (Vite)
- **phpmyadmin**: Interface web para gerenciar o MySQL

## 💡 Dicas

1. **Desenvolvimento com Vite**: Para ver mudanças em tempo real nos assets:
   ```powershell
   docker-compose exec node npm run dev
   ```

2. **Backup do banco**: Os dados do MySQL ficam salvos no volume `dbdata`, mesmo se você parar os containers.

3. **Performance**: Na primeira vez pode ser lento, mas depois fica rápido!

4. **Atualizar código**: Basta editar os arquivos normalmente. O Docker sincroniza automaticamente.

---

**Pronto! Agora você está pronto para desenvolver com Docker! 🚀**

Se tiver alguma dúvida, consulte o arquivo `docker/README.md` para mais detalhes técnicos.

