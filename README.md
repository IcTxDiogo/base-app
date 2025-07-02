# 🚀 Laravel React Boilerplate

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![React](https://img.shields.io/badge/React-19.x-61DAFB?style=for-the-badge&logo=react)
![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?style=for-the-badge&logo=typescript)
![TailwindCSS](https://img.shields.io/badge/Tailwind-4.x-38B2AC?style=for-the-badge&logo=tailwind-css)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker)

Boilerplate completo e moderno para projetos Laravel + React com ambiente Docker totalmente automatizado, testes configurados com coverage, e ferramentas de desenvolvimento otimizadas.

## 🚀 Instalação Rápida

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/seu-repo.git
cd seu-repo

# 2. Configure o ambiente
cp .env.example .env

# 3. Inicie tudo (instala dependências automaticamente)
./dev up
```

**Pronto!** Acesse http://localhost (Login: `test@base-app.com` / `password`)

## ✨ Stack Tecnológico

**Backend:** Laravel 12 • PHP 8.3 • PostgreSQL 16 • Redis 7 • Horizon • Reverb (WebSockets)  
**Frontend:** React 19 • TypeScript 5 • Inertia.js • Vite 6 • Tailwind CSS v4 • Shadcn/ui  
**DevOps:** Docker • Nginx • Mailpit • Pest (testes) • Xdebug (coverage)

## 🛠️ Comandos Principais

```bash
# Ambiente
./dev up/down/restart     # Gerenciar containers
./dev reset              # Reset completo do ambiente

# Laravel
./dev artisan [cmd]      # Comandos Artisan
./dev composer [cmd]     # Comandos Composer

# Frontend
./dev npm [cmd]          # Comandos NPM

# Testes & Coverage
./dev test               # Executar testes (paralelo)
./dev test:coverage      # Coverage no terminal (paralelo)
./dev test:coverage-html # Coverage HTML (paralelo) - storage/coverage/index.html
./dev test:debug         # Testes com Xdebug para debug remoto (paralelo)
./dev test:watch         # Modo watch (reexecuta ao alterar arquivos)

# Acesso
./dev shell              # Terminal container PHP
./dev root               # Terminal como root
./dev help               # Lista todos os comandos
```

## 🌐 Serviços

| Serviço     | URL                      | Descrição                 |
| ----------- | ------------------------ | ------------------------- |
| **App**     | http://localhost         | Aplicação principal       |
| **Mailpit** | http://localhost:8025    | Emails de desenvolvimento |
| **Horizon** | http://localhost/horizon | Dashboard de filas        |
| **Vite**    | http://localhost:5173    | Hot reload (dev)          |

## 🧪 Testes & Coverage

O projeto vem com **89% de cobertura** configurada:

```bash
# Executar testes (paralelo)
./dev test

# Ver coverage (paralelo)
./dev test:coverage

# Gerar relatório HTML (paralelo)
./dev test:coverage-html
```

### Debug Remoto (IDEs)

Configure sua IDE para debug remoto na **porta 9003**:

```bash
./dev test:debug  # Executa testes em paralelo com Xdebug ativo
```

**VSCode:** Instale "PHP Debug" e configure `launch.json`:

```json
{
    "name": "Listen for Xdebug (Docker)",
    "type": "php",
    "request": "launch",
    "port": 9003,
    "pathMappings": {
        "/var/www/html": "${workspaceFolder}"
    }
}
```

## 📁 Estrutura

```
app/                 # Código Laravel
├── Http/           # Controllers, Middleware, Requests
├── Models/         # Models Eloquent
└── Providers/      # Service Providers

resources/js/        # Frontend React/TypeScript
├── components/     # Componentes UI
├── pages/          # Páginas Inertia.js
├── layouts/        # Layouts da aplicação
└── types/          # Tipos TypeScript

docker/             # Configurações Docker
├── php/           # Container PHP + Xdebug
├── nginx/         # Servidor web
└── node/          # Frontend build

tests/              # Testes Pest
├── Feature/       # Testes de funcionalidade
└── Unit/          # Testes unitários
```

## 🚀 Melhorias Implementadas

### ✅ **Testes & Coverage**

- **Xdebug 3.x** configurado com zero warnings
- **Coverage HTML** em `storage/coverage/index.html`
- **Debug remoto** para IDEs (porta 9003)
- **89% de cobertura** nos testes existentes
- **Comandos automatizados** para todos os cenários

### ✅ **Ambiente Docker**

- **Auto-instalação** de dependências na primeira execução
- **Containers otimizados** com todas as ferramentas
- **Hot reload** para desenvolvimento
- **Logs limpos** sem warnings

### ✅ **Automação**

- **Script `./dev`** centraliza todos os comandos
- **Entrypoints inteligentes** detectam primeira execução
- **Migrations automáticas** na inicialização
- **Watch mode** para testes contínuos

## 🎯 Próximos Passos Sugeridos

1. **CI/CD**: GitHub Actions com coverage reports
2. **Linting**: PHP CS Fixer + ESLint no pipeline
3. **Security**: Análise automática de vulnerabilidades
4. **Performance**: Ferramentas de profiling
5. **Monitoring**: Observabilidade para produção

---

**🎉 Ambiente pronto para produção com desenvolvimento otimizado!**
