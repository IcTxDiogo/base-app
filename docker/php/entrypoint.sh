#!/bin/sh
set -e

# Esta lógica de setup só vai rodar no container principal da aplicação.
# Os outros containers (horizon, scheduler, reverb) vão pular este bloco.
if [ "$IS_PRIMARY_APP" = "true" ]; then
    FLAG_FILE="/var/www/html/storage/installed.flag"

    # Verifica se o setup inicial já foi feito
    if [ ! -f "$FLAG_FILE" ]; then
        echo "PRIMEIRA EXECUÇÃO: Configurando o ambiente no container principal..."

        # 1. Instala dependências do Composer
        composer install --no-interaction --no-progress --prefer-dist

        # 2. Gera a chave da aplicação
        php artisan key:generate

        # 3. Gera e configura as chaves do Reverb se o valor de REVERB_APP_ID estiver vazio
        if grep -q "^REVERB_APP_ID=$" .env; then # <-- LÓGICA CORRIGIDA AQUI
            echo "Configurando as chaves do Reverb no arquivo .env..."
            REVERB_SECRET=$(php artisan key:generate --show)
            # Usa sed para substituir as linhas vazias em vez de adicionar ao final
            sed -i "s|^REVERB_APP_ID=.*|REVERB_APP_ID=12345|" .env
            sed -i "s|^REVERB_APP_KEY=.*|REVERB_APP_KEY=reverb_key_local|" .env
            sed -i "s|^REVERB_APP_SECRET=.*|REVERB_APP_SECRET=${REVERB_SECRET}|" .env
            echo "Chaves do Reverb configuradas."
        fi

        # 4. gera uma app key para o .env.dusk.local
        if [ -f .env.dusk.local ]; then
            if grep -q "^APP_KEY=$" .env.dusk.local; then
                echo "Gerando APP_KEY para o arquivo .env.dusk.local..."
                DUSK_APP_KEY=$(php artisan key:generate --show)
                sed -i "s|^APP_KEY=.*|APP_KEY=${DUSK_APP_KEY}|" .env.dusk.local
                echo "APP_KEY para .env.dusk.local configurada."
            fi
        fi

        # 5. Aguarda o banco de dados estar pronto
        echo "Aguardando o banco de dados..."
        sleep 5

        # 6. Roda as migrations e seeds
        php artisan migrate:fresh --seed

        # 7. Cria o arquivo de flag para não executar este bloco novamente
        touch $FLAG_FILE
        echo "CONFIGURAÇÃO INICIAL CONCLUÍDA."
    else
        echo "Ambiente já configurado, iniciando container principal..."
    fi
else
    echo "Pulando configuração inicial para o container secundário ($(hostname))."
fi

# Este comando final executa para TODOS os containers.
exec "$@"
