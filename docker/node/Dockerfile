# Ficheiro: docker/node/Dockerfile (Versão Final e Corrigida)

FROM node:20-alpine

WORKDIR /var/www/html

# Copia os ficheiros de dependência e define o 'node' como dono.
COPY --chown=node:node package*.json ./

# Instala as dependências.
RUN npm install

# Copia o resto do código da aplicação.
COPY --chown=node:node . .

# --- A CORREÇÃO FINAL ESTÁ AQUI ---
# Copia o script de entrada para o local esperado e torna-o executável.
COPY docker/node/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
# -----------------------------------

# Define o Entrypoint que agora existe dentro da imagem.
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Comando padrão para iniciar o servidor Vite.
CMD ["npm", "run", "dev", "--", "--host"]
