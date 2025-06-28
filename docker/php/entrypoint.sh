#!/bin/sh

set -e

# Função para verificar se uma chave está vazia no ficheiro .env
is_key_empty() {
    # $1 é o nome da chave (ex: APP_KEY)
    # $2 é o padrão para verificar (ex: base64:.+)
    if ! grep -q "^$1=" .env; then
        # Se a linha da chave nem sequer existe, consideramos "vazia"
        return 0 # 0 significa 'true' em shell script (sucesso)
    fi

    # Extrai o valor depois do sinal de igual
    VALUE=$(grep "^$1=" .env | cut -d'=' -f2-)

    # Verifica se o valor extraído corresponde ao padrão de "preenchido"
    if echo "$VALUE" | grep -E -q "^$2$"; then
        return 1 # 1 significa 'false' (não está vazia)
    else
        return 0 # 0 significa 'true' (está vazia)
    fi
}


# Só executa a lógica de setup para o serviço principal 'app'
if [ "$1" = "php-fpm" ]; then

    # Se o ficheiro de trava não existir, roda a instalação completa
    if [ ! -f "storage/app/.docker_setup_complete" ]; then
        echo "🚀 Primeira inicialização do PHP detectada. Configurando o projeto..."

        # Define os ficheiros de ambiente a serem atualizados como uma string separada por espaços
        TARGET_ENV_FILES=".env"
        if [ -f ".env.dusk.local" ]; then
            TARGET_ENV_FILES="$TARGET_ENV_FILES .env.dusk.local"
            echo "ℹ️ Ficheiro .env.dusk.local encontrado. As chaves serão sincronizadas."
        fi

        # 1. Instala dependências do Composer
        composer install --no-interaction --no-progress --prefer-dist

        # 2. Gera a chave da aplicação, se não existir, e sincroniza
        if is_key_empty "APP_KEY" "base64:.+"; then
            echo "🔑 Gerando nova APP_KEY..."
            php artisan key:generate
            NEW_APP_KEY=$(grep "^APP_KEY=" .env | cut -d'=' -f2-)

            echo "🔄 Sincronizando APP_KEY nos ficheiros de ambiente..."
            for FILE in $TARGET_ENV_FILES; do # Loop sobre a string
                sed -i "s#^APP_KEY=.*#APP_KEY=${NEW_APP_KEY}#" "$FILE"
                echo "   ✓ APP_KEY atualizada em ${FILE}"
            done
        else
            echo "✅ APP_KEY já existe."
        fi

        # 3. Gera e insere as chaves do Reverb, se estiverem em falta
        if is_key_empty "REVERB_APP_KEY" ".+"; then
            echo "📡 Gerando novas chaves do Reverb..."
            REVERB_APP_ID=$(php -r 'echo rand(100000, 999999);')
            REVERB_KEY=$(php -r 'echo bin2hex(random_bytes(16));')
            REVERB_SECRET=$(php -r 'echo bin2hex(random_bytes(32));')

            echo "🔄 Sincronizando chaves do Reverb nos ficheiros de ambiente..."
            for FILE in $TARGET_ENV_FILES; do
                sed -i "s/^REVERB_APP_ID=.*/REVERB_APP_ID=${REVERB_APP_ID}/" "$FILE"
                sed -i "s/^REVERB_APP_KEY=.*/REVERB_APP_KEY=${REVERB_KEY}/" "$FILE"
                sed -i "s/^REVERB_APP_SECRET=.*/REVERB_APP_SECRET=${REVERB_SECRET}/" "$FILE"
                echo "   ✓ Chaves do Reverb atualizadas em ${FILE}"
            done
        else
            echo "✅ Chaves do Reverb já existem."
        fi

        # 4. Espera o banco de dados
        echo "⏳ Aguardando o banco de dados..."
        until php -r "try { new PDO('pgsql:host=postgres;port=5432;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (PDOException \$e) { exit(1); }"; do
            sleep 2
        done
        echo "✅ Banco de dados está pronto."

        # 5 Verifica se a base de dados de teste já existe
        if [ -z "${DB_TEST_DATABASE}" ]; then
            echo "❌ A variável de ambiente DB_TEST_DATABASE não está definida. A sair."
            exit 1
        fi
        echo "🔎 Verificando a base de dados de teste '${DB_TEST_DATABASE}'..."

        if PGPASSWORD="${DB_PASSWORD}" psql -lqt -h postgres -U "${DB_USERNAME}" -d "postgres" | cut -d \| -f 1 | grep -qw "${DB_TEST_DATABASE}"; then
            echo "✅ Base de dados de teste ('${DB_TEST_DATABASE}') já existe."
        else
            echo "🛠️ Base de dados de teste ('${DB_TEST_DATABASE}') não encontrada. A criar..."
            PGPASSWORD="${DB_PASSWORD}" psql -h postgres -U "${DB_USERNAME}" -d "postgres" -c "CREATE DATABASE ${DB_TEST_DATABASE} OWNER ${DB_USERNAME}"
            echo "✅ Base de dados de teste criada com sucesso."
        fi

        # 6. Roda migrações e seeds
        echo "🗃️ Executando migrações e seeds..."
        php artisan migrate --force --seed

        # 7. Cria o ficheiro de trava para não executar novamente
        touch storage/app/.docker_setup_complete
        echo "✅ Setup do PHP concluído."

    else
        echo "✅ Setup do PHP já concluído anteriormente."
    fi
fi

# Executa o comando principal que foi passado para o contentor (ex: php-fpm)
echo "🚀 Iniciando (PHP): $*"
exec "$@"