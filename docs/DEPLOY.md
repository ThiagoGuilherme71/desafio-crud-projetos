# Deploy com Docker

## Pre-requisitos
- Docker instalado
- Docker Compose instalado

## Deploy Rapido

### 1. Baixar arquivos de configuracao
curl -O https://raw.githubusercontent.com/ThiagoGuilherme71/desafio-crud-projetos/master/docker-compose.prod.yml
curl -O https://raw.githubusercontent.com/ThiagoGuilherme71/desafio-crud-projetos/master/nginx.conf
curl -O https://raw.githubusercontent.com/ThiagoGuilherme71/desafio-crud-projetos/master/.env.example.prod

### 2. Configurar ambiente
cp .env.example.prod .env
mkdir -p storage/{app,framework,logs} database
touch database/database.sqlite
docker run --rm thiagoguilherme71/desafio-crud-projetos:latest php artisan key:generate --show
Adicionar a chave no .env manualmente
docker run --rm thiagoguilherme71/desafio-crud-projetos:latest php artisan jwt:secret --show
Adicionar no .env manualmente

### 3. Subir os containers
docker-compose -f docker-compose.prod.yml up -d

### 4. Rodar migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

### 5. Acessar a aplicacao
Abra no navegador: http://localhost:8080

## Comandos Uteis
Ver logs: docker-compose -f docker-compose.prod.yml logs -f
Parar containers: docker-compose -f docker-compose.prod.yml down
Atualizar para nova versao: docker-compose -f docker-compose.prod.yml pull && docker-compose -f docker-compose.prod.yml up -d
Acessar container: docker-compose -f docker-compose.prod.yml exec app sh

## Troubleshooting
Erro de permissao: chmod -R 775 storage database
Limpar cache: docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear && docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
