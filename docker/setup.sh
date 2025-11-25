#!/bin/bash

echo "🚀 Configurando o projeto ErasmusConecta com Docker..."

# Copiar arquivo de ambiente se não existir
if [ ! -f .env ]; then
    echo "📝 Copiando arquivo de ambiente..."
    cp env.docker.example .env
    echo "✅ Arquivo .env criado. Por favor, configure as variáveis necessárias."
else
    echo "ℹ️  Arquivo .env já existe."
fi

# Construir e iniciar containers
echo "🔨 Construindo containers..."
docker-compose build

echo "🚀 Iniciando containers..."
docker-compose up -d

# Aguardar MySQL estar pronto
echo "⏳ Aguardando MySQL estar pronto..."
sleep 10

# Instalar dependências do Composer
echo "📦 Instalando dependências do Composer..."
docker-compose exec -T app composer install --no-interaction

# Instalar dependências do NPM
echo "📦 Instalando dependências do NPM..."
docker-compose exec -T node npm install

# Gerar chave da aplicação
echo "🔑 Gerando chave da aplicação..."
docker-compose exec -T app php artisan key:generate

# Executar migrações
echo "🗄️  Executando migrações..."
docker-compose exec -T app php artisan migrate --force

# Criar link simbólico do storage
echo "🔗 Criando link simbólico do storage..."
docker-compose exec -T app php artisan storage:link || true

# Configurar permissões
echo "🔐 Configurando permissões..."
docker-compose exec -T app chmod -R 775 storage bootstrap/cache || true

echo ""
echo "✅ Setup concluído!"
echo ""
echo "📋 Próximos passos:"
echo "   1. Acesse a aplicação em: http://localhost:8080"
echo "   2. Acesse o phpMyAdmin em: http://localhost:8081"
echo "      - Usuário: root"
echo "      - Senha: root"
echo "   3. Para executar os seeders: docker-compose exec app php artisan db:seed"
echo "   4. Para desenvolvimento com Vite: docker-compose exec node npm run dev"
echo ""

