FROM php:8.3-fpm-alpine

ARG UID=1000
ARG GID=1000

# Copia o executável do Composer primeiro
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Instala dependências do sistema e extensões PHP de forma otimizada
RUN apk add --no-cache \
        bash curl git libzip-dev icu-dev libxml2-dev postgresql-dev \
        oniguruma-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS linux-headers \
    # Compila e instala as extensões
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql bcmath zip opcache mbstring ctype xml intl pcntl gd sockets \
    && pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug \
    # Limpeza: remove o grupo de dependências de build que não são mais necessárias
    && apk del .build-deps

# Cria grupo e usuário
RUN addgroup -g ${GID} laravel && \
    adduser -u ${UID} -G laravel -s /bin/sh -D laravel

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o script de entrada
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Troca para o usuário laravel
USER laravel

# Define o Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Expõe a porta do PHP-FPM
EXPOSE 9000

# Comando padrão que o entrypoint vai executar
CMD ["php-fpm"]
