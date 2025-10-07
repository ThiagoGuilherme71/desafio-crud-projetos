# Imagem base
FROM php:8.2-fpm-alpine

# Instalação de dependências do sistema
RUN apk update && apk add --no-cache \
    bash \
    git \
    curl \
    libxml2-dev \
    libzip-dev \
    sqlite-dev \
    icu-dev \
    oniguruma-dev \
    && rm -rf /var/cache/apk/*

# Instalação de extensões PHP (removidas as que já vêm por padrão)
RUN docker-php-ext-install -j$(nproc) \
    pdo_sqlite \
    pdo_mysql \
    zip \
    bcmath \
    xml \
    pcntl \
    intl

# Instalação do Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# Diretório de trabalho
WORKDIR /var/www/html

# Ajustar permissões do diretório
RUN chown -R www-data:www-data /var/www/html

# Expor porta do PHP-FPM
EXPOSE 9000

# Iniciar PHP-FPM
CMD ["php-fpm"]
