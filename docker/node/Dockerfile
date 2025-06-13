FROM node:20-alpine

WORKDIR /var/www/html

# Instala dependências apenas quando package.json muda
COPY package*.json ./
RUN npm install

# Copia o resto do código
COPY . .

# Comando para iniciar o servidor de desenvolvimento
CMD ["npm", "run", "dev"]
