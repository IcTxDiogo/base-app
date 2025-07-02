#!/bin/sh
set -e

# Garante que o usuário 'node' seja o dono do diretório de trabalho.
# Isso corrige qualquer problema de permissão do volume montado.
chown -R node:node /var/www/html

# Agora, muda para o usuário 'node' para executar os comandos com as permissões corretas.
su-exec node sh <<'EOF'
set -e

# Define o caminho do arquivo de flag DENTRO deste bloco para garantir que ele exista.
FLAG_FILE="/var/www/html/node_modules/.installed.flag"

# Verifica se o arquivo de flag NÃO existe
if [ ! -f "$FLAG_FILE" ]; then
    echo "Primeira execução. Instalando dependências do Node..."

    # Instala as dependências do npm
    npm install

    # Cria o arquivo de flag
    touch "$FLAG_FILE"
    echo "Dependências do Node instaladas."
else
    echo "Dependências do Node já instaladas. Iniciando..."
fi
EOF

# Executa o comando principal do container (CMD do Dockerfile)
exec "$@"
