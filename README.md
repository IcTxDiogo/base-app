# Base App - Boilerplate Moderno

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![React](https://img.shields.io/badge/React-19.x-20232A?style=for-the-badge&logo=react)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-316192?style=for-the-badge&logo=postgresql)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker)

Este repositório é um **projeto casca (boilerplate)**, totalmente configurado e pronto para servir de base para novos projetos modernos. Ele já traz uma estrutura completa, com integração entre backend e frontend, ambiente Dockerizado, scripts de automação e exemplos de configuração para facilitar o início de qualquer aplicação.

> **Objetivo:** Fornecer um ponto de partida robusto, padronizado e produtivo para novos projetos, economizando tempo com configurações iniciais.

---

## 📋 Índice

- [✨ Tecnologias](#-tecnologias)
- [⚙️ Pré-requisitos](#-pré-requisitos)
- [🚀 Instalação](#-instalação)
- [🛠️ Uso e Comandos Úteis](#-uso-e-comandos-úteis)
- [🌐 Serviços e Portas](#-serviços-e-portas)
- [🤔 Troubleshooting](#-troubleshooting)

---

## ✨ Tecnologias

Este projeto foi construído com um stack moderno e robusto, totalmente containerizado com Docker:

- **Backend:** Laravel 12
- **Frontend:** React 19 com Vite 6 e TypeScript (.tsx)
- **PHP:** 8.2+
- **Estilização:** TailwindCSS 4
- **Banco de Dados:** PostgreSQL 15
- **Cache & Filas:** Redis
- **WebSockets:** Laravel Reverb
- **Testes:** Pest 3
- **Ambiente:** Docker & Docker Compose
- **Servidor Web:** Nginx

---

## ⚙️ Pré-requisitos

Antes de começar, garanta que você tem as seguintes ferramentas instaladas na sua máquina:

- [Docker](https://www.docker.com/products/docker-desktop/)
- [Git](https://git-scm.com/)
- **Para usuários Windows:** É altamente recomendado usar o [WSL2](https://learn.microsoft.com/pt-br/windows/wsl/install) para uma melhor performance e compatibilidade.

---

## 🚀 Instalação

Graças ao Docker e a um script de inicialização automatizado, colocar o projeto para rodar é muito simples.

**1. Clone o Repositório**

```bash
git clone [URL_DO_SEU_REPOSITORIO]
cd [NOME_DA_PASTA_DO_PROJETO]
```

**2. Configure o Ambiente**

Copie o arquivo de ambiente de exemplo. O script de inicialização cuidará de gerar a APP_KEY para você.

```bash
cp .env.example .env
```

**3. Suba os Contêineres**

Este é o comando mágico. Ele irá construir as imagens, iniciar todos os contêineres e, na primeira vez que for executado, o script de inicialização irá automaticamente:

Instalar as dependências do Composer.

Gerar a chave da aplicação.

Esperar o banco de dados ficar pronto.

Rodar as migrações e popular o banco (seeders).

```bash
docker compose up -d --build
```

## 🛠️ Uso e Comandos Úteis

Para facilitar a interação com o ambiente Docker no dia a dia, criamos um script de atalhos `(./dev)` na raiz do projeto.

Dica: Se você estiver em um ambiente Linux/macOS, pode criar um `alias` no seu `~/.zshrc` ou `~/.bashrc` para facilitar ainda mais: `alias dev='./dev'`
