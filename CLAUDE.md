# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

Authentication microservice built on Laravel 13 with a hexagonal / DDD layout. It issues
JWTs through an OAuth-style authorization-code flow. An Angular 17 SPA lives in
`resources/frontend` and runs as its own container in development; in the production image
both the API and the built frontend are served from a single container.

## Commands

Everything runs in Docker. The `Makefile` is the entry point:

- `make install` – composer install + npm install (frontend) + `.env` + build image
- `make start` / `make stop` – `docker compose up -d` / `down` (API on :8000, DB on :3306, frontend on :4200)
- `make build` – rebuild the `auth-microservice:latest` image
- `make test` – run the full PHPUnit suite inside the `auth` container (`--testdox`)
- `make bash` – shell into the `auth` container

### Tests

- Run a single test file/case inside the container:
  `docker exec -it -w /var/www/api auth ./vendor/bin/phpunit --filter CreateUserTest`
- Filter to one method: `./vendor/bin/phpunit --filter testFireUserCreated`
- Two suites (see `phpunit.xml`): **Application** = `tests/src` (domain/application services),
  **Controllers** = `tests/app` (HTTP + console).
- Tests force `IN_MEMORY_REPOSITORY=true` and a sqlite `:memory:` DB, so they never touch
  Doctrine or MySQL. `Tests\TestCase::initDefaultConfiguration()` seeds a `SigningKey` + `Client`
  before every test; assert domain events with `TestCase::assertEventsPublished()`.
- CI (`.github/workflows/tests.yml`) runs `php artisan test` on PRs; tags trigger
  `deploy.yml` (build + push Docker image).
- The Doctrine repositories, XML mappings, and MySQL persistence are **not exercised by the
  suite**. To check that path, run against the `auth-db` container with `-e IN_MEMORY_REPOSITORY=false`
  (e.g. `php artisan doctrine:info`, `php artisan doctrine:schema:validate`, or a `tinker` script).

### Migrations

Schema is managed by **Laravel** migrations (`database/migrations`, `php artisan migrate`),
even though runtime persistence uses Doctrine. In production the entrypoint runs
`php artisan migrate --force` automatically.

## Architecture

### Two-tree layout

- **`src/`** – framework-agnostic code. PSR-4: `Authentication\` → `src/Authentication`,
  `Shared\` → `src/Shared`. No Laravel facades here except inside `Infrastructure/Laravel`.
- **`app/`** – the Laravel adapter (`App\` → `app/`). All HTTP/console/framework wiring lives
  under `app/UI` and `app/Providers`.

Layering inside `src/<Context>/`:
- `Domain/Model/<Aggregate>/` – entities, repository **interfaces**, domain exceptions, events
- `Domain/Service/` – service **interfaces** (e.g. `EncodePassword`, `GenerateJwtToken`)
- `Application/Service/<Aggregate>/` – use cases, one class per action plus a matching
  `...Request` DTO (e.g. `CreateUser` + `CreateUserRequest`)
- `Infrastructure/Doctrine/...` – Doctrine repository implementations + XML mappings
- `Infrastructure/Laravel/...` – in-memory repositories and Laravel-backed service impls

### Request flow

Route (`app/UI/Http/Routes/oauth.php`, all prefixed `/oauth`) → single-action controller
(`__invoke`) → type-hinted `FormRequest` validator (`app/UI/Http/Validators/...`) →
build a `...Request` DTO → call an Application Service → return `response()->json()`.
Domain exceptions are converted to JSON responses centrally in `App\Exceptions\Handler::render`
(`DomainValidationException` → 422 with per-field errors).

### Dependency binding (`app/Providers`)

- `RepositoryServiceProvider` – binds every `*Repository` interface. Switches between Doctrine
  and in-memory implementations based on the `IN_MEMORY_REPOSITORY` env var. Doctrine repos are
  constructed manually from `$app['em']` + class metadata.
- `BindServiceProvider` – binds domain service interfaces to their infrastructure impls
  (JWT, password hashing, random strings, config access).
- `AuthServiceProvider` – registers the custom `jwt` auth driver.
- `EventServiceProvider` – subscribes `PersistDomainEventSubscriber` (unless in-memory).

### Domain events

Custom, **not** Laravel's event system. `Shared\Domain\Service\EventPublisher` is a process-wide
singleton that is never reset between tests; aggregates call
`EventPublisher::instance()->publish(new SomeEvent(...))` from constructors and mutators.
`PersistDomainEventSubscriber` writes each event to the `event` table via `DB::table()`.
Note: if a test errors out after `assertEventsPublished()` (e.g. a Mockery setup failure), its
mock subscriber stays registered and can make later tests fail with confusing event assertions.

### Persistence

Runtime queries use **Doctrine ORM** with XML mappings in
`src/Authentication/Infrastructure/Doctrine/Domain/Mapping/*.dcm.xml` (path configured in
`config/doctrine.php`). Aggregates are plain PHP objects with private properties and no ORM
annotations. `AppServiceProvider::boot()` pulls the native PDO out of the Doctrine
`EntityManager` and hands it to Laravel's `ConnectionInterface`, so Doctrine, `DB::table()`,
and migrations all share one connection.

Identifiers are plain `string` UUIDs everywhere (entity properties, constructor args, repository
signatures, event payloads). Id columns map with Doctrine's built-in `guid` type and
`<generator strategy="NONE"/>` — every aggregate id is supplied by the repository's `nextId()`,
never auto-generated by the ORM.

### Auth / token model

- Custom guard `App\Guards\JwtGuard` (default guard `api` in `config/auth.php`). It reads the
  `Authorization: Bearer` header, validates with `lcobucci/jwt`, and loads the `User` by the
  token's `jti` claim.
- Each `Client` owns its own signing key (`Client::signingKeyId()`); tokens are signed with
  the requesting client's key.
- Flow: `POST /oauth/authorize` (email + password + client + redirect URL → `AuthorizationCode`)
  then `POST /oauth/token` (code + client secret → signed JWT `AccessToken`).

### Console commands (`app/UI/Console/Commands`)

- `php artisan client:create` – interactive; prints the one-time client secret
- `php artisan generate:signing-key` – create/replace a signing key

## Conventions

- Application services never return arrays — they return domain objects that implement
  `JsonSerializable` (`jsonSerialize()` → `toArray()`).
- "Not found" is signalled by throwing a domain exception, not returning null; callers catch it
  (see `CreateUser` checking for an existing email).
- When adding a repository: define the interface in `Domain/Model`, then add **both** a Doctrine
  and an in-memory implementation and register both in `RepositoryServiceProvider`, or tests
  will break.
- Test namespaces carry an extra `AuthMicroservice` segment (`Tests\src\AuthMicroservice\...`)
  that does not correspond to a real namespace in `src/` — match the existing pattern of the
  sibling test when adding files.
