#!/bin/bash
echo "Deploy do CRUD Projetos"
echo "======================="
if ! command -v docker &> /dev/null; then
    echo "Docker nao esta instalado!"
    exit 1
fi
echo "Criando diretorios..."
mkdir -p storage/{app,framework/{cache,sessions,views},logs} database
touch database/database.sqlite
if [ ! -f .env ]; then
    echo "Criando arquivo .env..."
    cp .env.example.prod .env
    echo "Gerando APP_KEY..."
    APP_KEY=$(docker run --rm thiagoguilherme71/desafio-crud-projetos:latest php artisan key:generate --show)
    sed -i.bak "s|APP_KEY=|APP_KEY=$APP_KEY|g" .env
    echo "Gerando JWT_SECRET..."
    JWT_SECRET=$(docker run --rm thiagoguilherme71/desafio-crud-projetos:latest php artisan jwt:secret --show)
    sed -i.bak "s|JWT_SECRET=|JWT_SECRET=$JWT_SECRET|g" .env
    rm .env.bak 2>/dev/null
fi
echo "Ajustando permissoes..."
chmod -R 775 storage database
echo "Subindo containers..."
docker-compose -f docker-compose.prod.yml up -d
echo "Aguardando containers iniciarem..."
sleep 5
echo "Rodando migrations..."
docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force
echo ""
echo "Deploy concluido com sucesso!"
echo "Acesse: http://localhost:8080"
echo ""
echo "Comandos uteis:"
echo "  - Ver logs: docker-compose -f docker-compose.prod.yml logs -f"
echo "  - Parar: docker-compose -f docker-compose.prod.yml down"
