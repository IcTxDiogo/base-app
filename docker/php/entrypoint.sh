#!/bin/sh
set -e

# Só executa a lógica de setup para o serviço principal 'app'
if [ "$1" = "php-fpm" ]; then

    # Se o ficheiro de trava não existir, roda a instalação completa
    if [ ! -f "storage/app/.docker_setup_complete" ]; then
        echo "🚀 Primeira inicialização do PHP detectada. Configurando o projeto..."

        # 1. Instala dependências do Composer
        composer install --no-interaction --no-progress --prefer-dist

        # 2. Gera a chave da aplicação, se não existir
        if ! grep -q "APP_KEY=base64:.*" .env; then
            echo "🔑 Gerando APP_KEY..."
            php artisan key:generate
        fi

        # 3. Gera as chaves do Reverb, se não existirem
        if ! grep -q "REVERB_APP_KEY=.*" .env; then
            echo "📡 Gerando chaves do Reverb..."
            php artisan reverb:install
        fi

        # 4. Espera o banco de dados
        echo "⏳ Aguardando o banco de dados..."
        until php -r "try { new PDO('pgsql:host=postgres;port=5432;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (PDOException \$e) { exit(1); }"; do
            sleep 2
        done
        echo "✅ Banco de dados está pronto."

        # 5. Roda migrações e seeds
        echo "🗃️ Executando migrações e seeds..."
        php artisan migrate --force --seed

        # 6. Cria o ficheiro de trava para não executar novamente
        touch storage/app/.docker_setup_complete
        echo "✅ Setup do PHP concluído."

    else
        echo "✅ Setup do PHP já concluído anteriormente."
    fi
fi

# Executa o comando principal que foi passado para o contentor (ex: php-fpm)
echo "🚀 Iniciando (PHP): $*"
exec "$@"
