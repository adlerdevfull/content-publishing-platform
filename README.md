# Desafio 4 — Content Management & Dynamic Publishing Platform

---

## 🇬🇧 English

Scalable API for managing content, categories, versions and publishing permissions with i18n support and full-text search.

### Stack

- **Backend**: PHP 8.2 + Laravel 11
- **Database**: PostgreSQL 16 (GIN full-text index)
- **Cache**: Redis 7
- **Auth**: JWT + RBAC (Spatie Permissions)
- **Infra**: Docker + docker-compose + GitHub Actions CI

### Architecture

Hexagonal (Ports & Adapters) with tactical DDD:

```
src/Domain/         → Pure business rules
src/Application/    → Use cases (Command Handlers)
src/Infrastructure/ → Eloquent, Redis
app/                → HTTP layer (Controllers, Resources)
```

### How to run

```bash
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
docker compose exec app php artisan migrate --seed
```

- API: http://localhost:8000/api/v1

**Login**: admin@platform.test / password

### Key features

- Automatic versioning — each edit saves the previous version
- Restore — roll back to any previous version
- Approval workflow — `draft` → `in_review` → `approved` → `published` → `archived`
- Concurrency control — per-user lock prevents simultaneous editing
- i18n — `translations` field with multi-language support
- Full-text search — PostgreSQL GIN index with Spanish dictionary
- Secure upload — MIME type and size validation (max 10MB)
- Published content cached in Redis (TTL 10min)

### Domain flows

**Content**: `draft` → `in_review` → `approved` → `published` → `archived`

### Tests

```bash
docker compose exec app vendor/bin/pest --coverage --min=75
```

---

## 🇪🇸 Español

API escalable para gestionar contenidos, categorías, versiones y permisos de publicación con soporte i18n y búsqueda de texto completo.

### Stack

- **Backend**: PHP 8.2 + Laravel 11
- **Base de datos**: PostgreSQL 16 (índice GIN full-text)
- **Caché**: Redis 7
- **Auth**: JWT + RBAC (Spatie Permissions)
- **Infra**: Docker + docker-compose + GitHub Actions CI

### Arquitectura

Hexagonal (Ports & Adapters) con DDD táctico:

```
src/Domain/         → Reglas de negocio puras
src/Application/    → Casos de uso (Command Handlers)
src/Infrastructure/ → Eloquent, Redis
app/                → Capa HTTP (Controllers, Resources)
```

### Cómo ejecutar

```bash
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
docker compose exec app php artisan migrate --seed
```

- API: http://localhost:8000/api/v1

**Login**: admin@platform.test / password

### Funcionalidades clave

- Versionado automático — cada edición guarda la versión anterior
- Restauración — volver a cualquier versión anterior
- Flujo de aprobación — `draft` → `in_review` → `approved` → `published` → `archived`
- Control de concurrencia — bloqueo por usuario impide edición simultánea
- i18n — campo `translations` con soporte multiidioma
- Búsqueda full-text — índice GIN de PostgreSQL con diccionario español
- Subida segura — validación de tipo MIME y tamaño (máx. 10MB)
- Contenido publicado en caché Redis (TTL 10min)

### Flujos de dominio

**Contenido**: `draft` → `in_review` → `approved` → `published` → `archived`

### Tests

```bash
docker compose exec app vendor/bin/pest --coverage --min=75
```

---

## 🇧🇷 Português

API escalável para gerenciar conteúdos, categorias, versões e permissões de publicação com suporte a i18n e busca textual.

### Stack

- **Backend**: PHP 8.2 + Laravel 11
- **Banco de dados**: PostgreSQL 16 (índice GIN full-text)
- **Cache**: Redis 7
- **Auth**: JWT + RBAC (Spatie Permissions)
- **Infra**: Docker + docker-compose + GitHub Actions CI

### Arquitetura

Hexagonal (Ports & Adapters) com DDD tático:

```
src/Domain/         → Regras de negócio puras
src/Application/    → Casos de uso (Command Handlers)
src/Infrastructure/ → Eloquent, Redis
app/                → Camada HTTP (Controllers, Resources)
```

### Como executar

```bash
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
docker compose exec app php artisan migrate --seed
```

- API: http://localhost:8000/api/v1

**Login**: admin@platform.test / password

### Funcionalidades principais

- Versionamento automático — cada edição salva a versão anterior
- Restauração — voltar a qualquer versão anterior
- Workflow de aprovação — `draft` → `in_review` → `approved` → `published` → `archived`
- Controle de concorrência — lock por usuário impede edição simultânea
- i18n — campo `translations` com suporte a múltiplos idiomas
- Busca full-text — índice GIN PostgreSQL com dicionário espanhol
- Upload seguro — validação de MIME type e tamanho (máx. 10MB)
- Conteúdos publicados em cache Redis (TTL 10min)

### Fluxos de domínio

**Conteúdo**: `draft` → `in_review` → `approved` → `published` → `archived`

### Testes

```bash
docker compose exec app vendor/bin/pest --coverage --min=75
```
