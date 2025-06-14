FROM php:8.3-fpm-alpine

ARG UID=1000
ARG GID=1000

# Instala dependências do sistema e extensões PHP
RUN apk add --no-cache \
    bash curl git \
    autoconf gcc g++ make libc-dev \
    libzip-dev icu-dev libxml2-dev postgresql-dev oniguruma-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql bcmath zip opcache mbstring ctype xml intl pcntl gd sockets \
    && pecl install redis && docker-php-ext-enable redis \
    && pecl install xdebug && docker-php-ext-enable xdebug

# Cria grupo e usuário
RUN addgroup -g ${GID} laravel && \
    adduser -u ${UID} -G laravel -s /bin/sh -D laravel

# Copia o executável do Composer da imagem oficial para o nosso PATH
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copia script de entrada
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Define o diretório de trabalho
WORKDIR /var/www/html

# Troca para o usuário laravel
USER laravel

# Define o Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Expõe a porta do PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]
