#!/bin/sh

# O arquivo de flag que indica se o setup já foi concluído
SETUP_FLAG_FILE="/var/www/html/storage/app/.setup_complete"

# Verifica se o setup precisa ser executado
if [ ! -f "$SETUP_FLAG_FILE" ]; then
    echo "🚀 Primeira inicialização detectada. Configurando o projeto..."

    # 1. Copia .env e gera a chave
    if [ ! -f ".env" ]; then
        echo "📋 Copiando .env.example para .env"
        cp .env.example .env
        echo "🔑 Gerando APP_KEY..."
        /usr/local/bin/php artisan key:generate
    fi

    # 2. Instalar dependências do Composer
    echo "🔧 Instalando dependências com Composer..."
    composer install --no-interaction --no-progress --prefer-dist

    # 3. Esperar o banco de dados
    echo "⏳ Aguardando o banco de dados..."
    until /usr/local/bin/php -r "try { new PDO('pgsql:host=postgres;port=5432;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (PDOException \$e) { exit(1); }"; do
        sleep 2
    done
    echo "✅ Banco de dados está pronto."

    # 4. Executar migrações e seeds
    echo "🗃️ Executando migrações e seeds..."
    /usr/local/bin/php artisan migrate --force --seed

    # 5. Criar o arquivo de flag
    echo "✅ Setup concluído. Criando flag."
    touch "$SETUP_FLAG_FILE"

else
    echo "✅ Setup já foi concluído anteriormente. Pulando."
fi

# Executa o comando principal do contêiner (ex: php-fpm)
echo "🚀 Iniciando: $*"
exec "$@"
