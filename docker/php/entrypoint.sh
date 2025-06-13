#!/bin/sh

# Verifica se o setup precisa ser executado
if [ "$1" = "php-fpm" ]; then
    echo "📦 Verificando dependências PHP..."
    if [ ! -d "vendor" ]; then
        echo "🚀 Primeira inicialização detectada. Configurando o projeto..."

        # 1. Instalar dependências do Composer
        echo "🔧 Instalando dependências com Composer..."
        composer install --no-interaction --no-progress --prefer-dist

        # 2. gera a chave
        echo "🔑 Gerando APP_KEY..."
        /usr/local/bin/php artisan key:generate

        # 3. Esperar o banco de dados
        echo "⏳ Aguardando o banco de dados..."
        until /usr/local/bin/php -r "try { new PDO('pgsql:host=postgres;port=5432;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (PDOException \$e) { exit(1); }"; do
            sleep 2
        done
        echo "✅ Banco de dados está pronto."

        # 4. Executar migrações e seeds
        echo "🗃️ Executando migrações e seeds..."
        /usr/local/bin/php artisan migrate --force --seed

    else
        echo "✅ Dependências PHP já instaladas."
    fi
fi

# Executa o comando principal do contêiner (ex: php-fpm)
echo "🚀 Iniciando: $*"
exec "$@"
