# 📊 CRUD PROJETOS: Gerenciamento Simplificado

Este projeto é uma **API RESTful** desenvolvida em **Laravel** que implementa as funcionalidades básicas de um **CRUD (Create, Read, Update, Delete)** para gerenciamento de projetos. A autenticação é realizada via **JSON Web Token (JWT)**.

---

## 📌 Resumo do Projeto

O sistema foi construído para atender à História de Usuário: **CRUD de Projetos**. Seu objetivo é fornecer uma ferramenta para que **usuários autenticados** possam criar, visualizar, editar e excluir projetos de forma eficiente.

### Funcionalidades Chave:
* **Autenticação JWT:** Todas as rotas de gerenciamento de projetos são protegidas.
* **Regras de Negócio Implementadas:** Validação de campos obrigatórios (Nome único) e controle de permissões de acesso.
* **Atributos de Projeto:** Inclui Nome (Obrigatório), Descrição (Opcional), Status (Ativo/Inativo) e Orçamento.

---

## 🚀 Configuração e Inicialização do Projeto (Docker)
# 1️⃣ Clonar o projeto
git clone https://github.com/ThiagoGuilherme71/desafio-crud-projetos.git <br>
cd desafio-crud-projetos

# 2️⃣ Criar o .env
cp .env.example .env

#  Exporta UID e GID do usuário atual (evita problemas de permissão)
export UID=$(id -u)
export GID=$(id -g)

# Adiciona UID e GID ao .env para evitar problemas de permissão
echo -e "\nUID=$(id -u)\nGID=$(id -g)" >> .env

# 3️⃣ Subir os containers
sudo docker compose up -d --build
# ou
sudo docker-compose up -d --build

# 4️⃣ Acessar o container
sudo docker compose exec app bash
# ou
sudo docker-compose exec app bash

# 5️⃣ Dentro do container, rodar:
composer install <br>
composer update

# Criar banco SQLite
mkdir -p database <br>
touch database/database.sqlite

# Gerar chave e configurar JWT
php artisan key:generate <br>
php artisan jwt:secret <br>

# Executar migrations
php artisan migrate <br>

# Limpar caches
php artisan config:clear <br>
php artisan cache:clear <br>
php artisan route:clear <br>

# Em casos de erro de permissão após seguir todo o processo altere a permissão:
sudo chown -R $USER:$USER vendor storage bootstrap/cache database

exit

