# Usa uma imagem base do PHP 8.3 com FPM no Alpine Linux
FROM php:8.3-fpm-alpine

# Define o diretório de trabalho
WORKDIR /var/www/html

# Instala dependências do sistema, incluindo dos2unix e linux-headers para Xdebug
RUN apk add --no-cache \
    build-base \
    autoconf \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    dos2unix \
    linux-headers

# Instala as extensões do PHP necessárias para o Laravel e Horizon
RUN docker-php-ext-install pdo pdo_pgsql pgsql bcmath zip mbstring pcntl

# Instala a extensão do Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instala o Xdebug para coverage de testes
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copia configurações personalizadas do PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Instala o Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia o script de entrypoint e corrige seu formato e permissões
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Cria um usuário e grupo 'sail' para evitar problemas de permissão.
RUN addgroup -g 1000 sail && \
    adduser -u 1000 -G sail -s /bin/sh -D sail

# Agora, muda para o usuário 'sail'
USER sail

# Define o entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# O comando padrão a ser executado quando o container iniciar
CMD ["php-fpm"]
