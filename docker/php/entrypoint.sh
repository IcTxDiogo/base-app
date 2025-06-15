#!/bin/sh

# Só executa a lógica de setup para o serviço principal 'app'
if [ "$1" = "php-fpm" ]; then

    # Se o ficheiro de trava não existir, roda a instalação completa
    if [ ! -f "storage/app/.docker_setup_complete" ]; then
        echo "🚀 Primeira inicialização do PHP detectada. Configurando o projeto..."

        # Instala dependências do Composer
        composer install --no-interaction --no-progress --prefer-dist

        # Gera a chave da aplicação
        php artisan key:generate

        # Espera o banco de dados
        echo "⏳ Aguardando o banco de dados..."
        until php -r "try { new PDO('pgsql:host=postgres;port=5432;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (PDOException \$e) { exit(1); }"; do
            sleep 2
        done
        echo "✅ Banco de dados está pronto."

        # Roda migrações e seeds
        php artisan migrate --force --seed

        # Cria o ficheiro de trava para não executar novamente
        touch storage/app/.docker_setup_complete
        echo "✅ Setup do PHP concluído."

    else
        echo "✅ Setup do PHP já concluído anteriormente."
    fi
fi

# Executa o comando principal que foi passado para o contentor (ex: php-fpm)
echo "🚀 Iniciando (PHP): $*"
exec "$@"
