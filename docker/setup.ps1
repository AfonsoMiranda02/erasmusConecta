# Script de setup para Windows PowerShell

Write-Host "Configurando o projeto ErasmusConecta com Docker..." -ForegroundColor Cyan

# Mudar para o diretorio raiz do projeto
$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

# Copiar arquivo de ambiente se nao existir
if (-not (Test-Path .env)) {
    Write-Host "Copiando arquivo de ambiente..." -ForegroundColor Yellow
    Copy-Item env.docker.example .env
    Write-Host "Arquivo .env criado. Por favor, configure as variaveis necessarias." -ForegroundColor Green
} else {
    Write-Host "Arquivo .env ja existe." -ForegroundColor Blue
}

# Construir e iniciar containers
Write-Host "Construindo containers..." -ForegroundColor Yellow
docker-compose build

Write-Host "Iniciando containers..." -ForegroundColor Yellow
docker-compose up -d

# Aguardar MySQL estar pronto
Write-Host "Aguardando MySQL estar pronto..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

# Instalar dependencias do Composer
Write-Host "Instalando dependencias do Composer..." -ForegroundColor Yellow
docker-compose exec -T app composer install --no-interaction

# Instalar dependencias do NPM
Write-Host "Instalando dependencias do NPM..." -ForegroundColor Yellow
docker-compose exec -T node npm install

# Gerar chave da aplicacao
Write-Host "Gerando chave da aplicacao..." -ForegroundColor Yellow
docker-compose exec -T app php artisan key:generate

# Configurar .env para Docker
Write-Host "Configurando variaveis do .env para Docker..." -ForegroundColor Yellow
docker-compose exec -T app php -r "`$env = file_get_contents('.env'); `$env = preg_replace('/DB_HOST=.*/', 'DB_HOST=db', `$env); `$env = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=root', `$env); `$env = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=erasmus_conecta', `$env); `$env = preg_replace('/REDIS_HOST=.*/', 'REDIS_HOST=redis', `$env); file_put_contents('.env', `$env);"

# Limpar cache de configuracao
Write-Host "Limpando cache..." -ForegroundColor Yellow
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan cache:clear

# Executar migracoes
Write-Host "Executando migracoes..." -ForegroundColor Yellow
docker-compose exec -T app php artisan migrate --force

# Criar link simbolico do storage
Write-Host "Criando link simbolico do storage..." -ForegroundColor Yellow
docker-compose exec -T app php artisan storage:link 2>$null

# Build do Vite (CSS/JS compilados)
Write-Host "Compilando assets com Vite (Tailwind CSS)..." -ForegroundColor Yellow
docker-compose exec -T node npm run build

Write-Host ""
Write-Host "Setup concluido!" -ForegroundColor Green
Write-Host ""
Write-Host "Setup concluido!" -ForegroundColor Green
Write-Host ""
Write-Host "Proximos passos:" -ForegroundColor Cyan
Write-Host "   1. Acesse a aplicacao em: http://localhost:8080" -ForegroundColor White
Write-Host "   2. Acesse o phpMyAdmin em: http://localhost:8081" -ForegroundColor White
Write-Host "      - Usuario: root" -ForegroundColor Gray
Write-Host "      - Senha: root" -ForegroundColor Gray
Write-Host "   3. Para executar os seeders: docker-compose exec app php artisan db:seed" -ForegroundColor White
Write-Host ""
Write-Host "Desenvolvimento com Vite e Tailwind CSS:" -ForegroundColor Cyan
Write-Host "   - Para desenvolvimento (hot-reload): docker-compose exec node npm run dev" -ForegroundColor White
Write-Host "   - Para build de producao: docker-compose exec node npm run build" -ForegroundColor White
Write-Host ""
Write-Host "Nota: Os assets ja foram compilados. Se fizer alteracoes no CSS/JS," -ForegroundColor Yellow
Write-Host "      execute 'npm run build' novamente ou use 'npm run dev' para desenvolvimento." -ForegroundColor Yellow
Write-Host ""
