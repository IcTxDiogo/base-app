#!/bin/sh

# Ficheiro: docker/node/entrypoint.sh

# Só executa a lógica de setup se a pasta node_modules não existir.
# Esta lógica é mais simples, pois a reinstalação do npm é rápida.
if [ ! -d "node_modules" ]; then
    echo "🚀 Pasta node_modules não encontrada. Instalando dependências..."
    npm install
    echo "✅ Dependências NPM instaladas."
else
    echo "✅ Dependências NPM já existem."
fi

# Executa o comando principal que foi passado para o contentor (ex: npm run dev)
echo "🚀 Iniciando (Node): $*"
exec "$@"
