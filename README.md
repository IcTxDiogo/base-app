# Base App - Boilerplate Moderno

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![React](https://img.shields.io/badge/React-19.x-20232A?style=for-the-badge&logo=react)
![Pest](https://img.shields.io/badge/Pest-Coverage-brightgreen?style=for-the-badge&logo=pest)
![Dusk](https://img.shields.io/badge/Dusk-Ready-18b2b6?style=for-the-badge&logo=laravel)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker)

Este repositório é um **projeto casca (boilerplate)**, totalmente configurado e pronto para servir de base para novos projetos modernos. Ele já traz uma estrutura completa, com integração entre backend e frontend, ambiente Dockerizado, automação de testes (backend e frontend), scripts de desenvolvimento e exemplos de configuração para facilitar o início de qualquer aplicação.

> **Objetivo:** Fornecer um ponto de partida robusto, padronizado e produtivo, economizando dezenas de horas com configurações iniciais de ambiente e testes.

---

## 📋 Índice

- [✨ Tecnologias e Ferramentas](#-tecnologias-e-ferramentas)
- [⚙️ Pré-requisitos](#-pré-requisitos)
- [🚀 Instalação](#-instalação)
- [🛠️ Centro de Comando: O Script `dev`](#-centro-de-comando-o-script-dev)
- [🧪 Testes](#-testes)
- [🌐 Serviços e Portas](#-serviços-e-portas)

---

## ✨ Tecnologias e Ferramentas

Este projeto foi construído com um stack moderno e robusto, totalmente containerizado com Docker:

- **Backend:** Laravel 12
- **Frontend:** React 19 com Vite 6 e TypeScript (`.tsx`)
- **PHP:** 8.3+
- **Estilização:** TailwindCSS 4
- **Banco de Dados:** PostgreSQL 15
- **Cache & Filas:** Redis
- **WebSockets:** Laravel Reverb
- **Servidor Web:** Nginx
- **Ambiente:** Docker & Docker Compose

#### Testes e Qualidade

O boilerplate vem pré-configurado com uma suite de testes completa e ferramentas de automação.

- **Testes de Backend:** Pest 3
- **Testes de Browser (End-to-End):** Laravel Dusk
- **Automação:** Scripts de Shell para gestão do ambiente e execução de testes.

---

## ⚙️ Pré-requisitos

Antes de começar, garanta que você tem as seguintes ferramentas instaladas na sua máquina:

- [Docker](https://www.docker.com/products/docker-desktop/)
- [Git](https://git-scm.com/)
- **Para usuários Linux (se for usar o `pest-watch`):** `inotify-tools` (`sudo apt install inotify-tools`)
- **Para usuários Windows:** É altamente recomendado usar o [WSL2](https://learn.microsoft.com/pt-br/windows/wsl/install) para uma melhor performance e compatibilidade.

---

## 🚀 Instalação

Graças ao Docker e a um script de inicialização automatizado, colocar o projeto para rodar é muito simples.

**1. Clone o Repositório**

```bash
git clone [URL_DO_SEU_REPOSITORIO]
cd [NOME_DA_PASTA_DO_PROJETO]
```

**2. Configure os Ambientes**

Copie os ficheiros de ambiente de exemplo. O script de inicialização cuidará de gerar e sincronizar as chaves da aplicação (`APP_KEY` e Reverb).

```bash
cp .env.example .env
cp .env.dusk.example .env.dusk.local
```

Após copiar, **edite os ficheiros `.env` e `.env.dusk.local`** para preencher os valores necessários (como `DB_USERNAME` e `DB_PASSWORD`).

**3. Suba os Contêineres**

Este é o comando mágico. Ele irá construir as imagens, iniciar todos os contêineres e, na primeira vez que for executado, o script de inicialização irá automaticamente:

- Instalar as dependências do Composer e NPM.
- Gerar e sincronizar as chaves da aplicação.
- Esperar o banco de dados ficar pronto.
- Criar a base de dados de desenvolvimento **e** a de testes.
- Rodar as migrações e popular o banco (seeders).

```bash
docker compose up -d --build
```

Após a conclusão, a sua aplicação estará disponível em `http://localhost:8080`.

---

## 🛠️ Centro de Comando: O Script `dev`

Para facilitar a interação com o ambiente Docker no dia a dia, criamos um script de atalhos (`./dev`) na raiz do projeto. Para ver todos os comandos, execute `./dev help`.

| Categoria           | Comando                  | Descrição                                                                 |
| :------------------ | :----------------------- | :------------------------------------------------------------------------ |
| **Ambiente Docker** | `up`, `down`, `ps`, etc. | Executa qualquer comando nativo do `docker compose`.                      |
|                     | `reset`                  | Força a reinstalação das dependências na próxima subida do ambiente.      |
| **Desenvolvimento** | `artisan ...`            | Executa comandos `artisan`.                                               |
|                     | `composer ...`           | Executa comandos `composer`.                                              |
|                     | `npm ...`                | Executa comandos `npm` no container do Node.js.                           |
|                     | `bash` / `shell`         | Acede ao terminal do container da aplicação.                              |
| **Suite Completa**  | `test`                   | Executa **todos** os testes: Pest (em paralelo) e depois Dusk.            |
| **Testes Backend**  | `pest-test`              | Roda os testes do Pest **em paralelo por padrão** para máxima velocidade. |
|                     | `pest-watch`             | Roda os testes do Pest em modo "watch" (não paralelo).                    |
|                     | `pest-coverage`          | Gera o relatório de cobertura do Pest **(executado em paralelo)**.        |
| **Testes Frontend** | `dusk-test`              | Roda os testes de browser com o Dusk (não suporta paralelismo).           |
|                     | `dusk-watch`             | Roda os testes do Dusk em modo "watch".                                   |
|                     | `dusk-coverage`          | Gera o relatório de cobertura do Dusk.                                    |

---

## 🧪 Testes

Este boilerplate está preparado para uma estratégia de testes robusta, dividida em duas categorias principais.

### Testes de Backend (Pest)

- **O que testam:** A lógica de negócio, APIs, controllers, services, etc.
- **Onde vivem:** `tests/Domains/`
- **Como executar:** `./dev pest-test`
- **Base de Dados:** Usam uma base de dados SQLite em memória para máxima velocidade e isolamento.

### Testes de Browser (Dusk)

- **O que testam:** O fluxo real do utilizador, interações na interface, e a integração entre o frontend React e o backend Laravel.
- **Onde vivem:** `tests/Browser/`
- **Como executar:** `./dev dusk-test`
- **Base de Dados:** Usam uma base de dados PostgreSQL dedicada (`app_test`), que é criada e migrada automaticamente, garantindo um ambiente de teste fiel ao de produção.

---

## 🌐 Serviços e Portas

Os seguintes serviços são expostos na sua máquina local:

| Serviço                 | Porta Local             | Descrição                                                         |
| :---------------------- | :---------------------- | :---------------------------------------------------------------- |
| **Aplicação (Nginx)**   | `http://localhost:8080` | Ponto de entrada principal da aplicação.                          |
| **PostgreSQL**          | `localhost:5432`        | Para conectar um cliente de BD como DBeaver ou TablePlus.         |
| **Mailpit**             | `http://localhost:8025` | Interface web para visualizar emails enviados em desenvolvimento. |
| **Redis Commander**     | `http://localhost:8081` | (Se incluído) Interface web para visualizar o Redis.              |
| **Reverb (WebSockets)** | `ws://localhost:9090`   | Para depuração direta do servidor de WebSockets.                  |
