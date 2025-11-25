# Docker Setup - ErasmusConecta

Este projeto está configurado para funcionar com Docker e Docker Compose.

## Pré-requisitos

- Docker Desktop instalado e em execução
- Docker Compose (geralmente incluído no Docker Desktop)

## Configuração Inicial

### Opção 1: Script Automático (Recomendado)

**Linux/Mac:**
```bash
chmod +x docker/setup.sh
./docker/setup.sh
```

**Windows (PowerShell):**
```powershell
.\docker\setup.ps1
```

### Opção 2: Manual

1. **Copie o arquivo de ambiente:**
   ```bash
   cp env.docker.example .env
   ```

2. **Inicie os containers:**
   ```bash
   docker-compose up -d
   ```

3. **Gere a chave da aplicação:**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

4. **Instale as dependências do Composer:**
   ```bash
   docker-compose exec app composer install
   ```

5. **Instale as dependências do NPM:**
   ```bash
   docker-compose exec node npm install
   ```

6. **Execute as migrações:**
   ```bash
   docker-compose exec app php artisan migrate
   ```

7. **Execute os seeders (opcional):**
   ```bash
   docker-compose exec app php artisan db:seed
   ```

8. **Crie o link simbólico do storage:**
   ```bash
   docker-compose exec app php artisan storage:link
   ```

## Comandos Úteis

### Iniciar os containers
```bash
docker-compose up -d
```

### Parar os containers
```bash
docker-compose down
```

### Ver os logs
```bash
docker-compose logs -f
```

### Acessar o container PHP
```bash
docker-compose exec app bash
```

### Executar comandos Artisan
```bash
docker-compose exec app php artisan [comando]
```

### Executar comandos Composer
```bash
docker-compose exec app composer [comando]
```

### Executar comandos NPM
```bash
docker-compose exec node npm [comando]
```

### Reconstruir os containers
```bash
docker-compose build --no-cache
```

### Limpar volumes (apaga dados do banco)
```bash
docker-compose down -v
```

## Serviços Disponíveis

- **Aplicação Laravel**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3307
- **Redis**: localhost:6379

## Estrutura dos Arquivos Docker

- `Dockerfile`: Configuração da imagem PHP
- `docker-compose.yml`: Orquestração dos serviços
- `docker/nginx/default.conf`: Configuração do Nginx
- `docker/php/local.ini`: Configurações PHP
- `docker/mysql/my.cnf`: Configurações MySQL

## Desenvolvimento

Para desenvolvimento com hot-reload do Vite:

```bash
# Inicie os containers
docker-compose up -d

# Execute o Vite em modo desenvolvimento (em um terminal separado)
docker-compose exec node npm run dev
```

Ou execute o Vite diretamente no seu terminal local se tiver Node.js instalado:
```bash
npm run dev
```

## Produção

Para build de produção:

```bash
docker-compose exec node npm run build
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

