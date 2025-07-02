# Usa uma imagem base do Node.js no Alpine Linux
FROM node:20-alpine

# Instala dependências, incluindo dos2unix e su-exec
# dos2unix corrige problemas de quebra de linha (CRLF vs LF)
RUN apk add --no-cache su-exec dos2unix

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o script de entrypoint
COPY docker/node/entrypoint.sh /usr/local/bin/entrypoint.sh

# Corrige o formato do arquivo e o torna executável
RUN dos2unix /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Define o entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# O comando padrão para iniciar o servidor de desenvolvimento Vite
CMD ["npm", "run", "dev"]
