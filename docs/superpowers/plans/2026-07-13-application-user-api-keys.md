# Application User API Keys Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build Application API endpoints that create, list, and delete client account API keys for a specific Pelican user.

**Architecture:** Add a focused Application Users API key controller, request classes, and transformer. Reuse `User::createToken()` for token generation and Pelican's Fractal serializer for responses.

**Tech Stack:** PHP 8.3+, Laravel 13, Sanctum token model customization, Pest/PHPUnit integration tests, Spatie Fractal.

## Global Constraints

- No new dependencies.
- Do not alter existing Client API endpoint behavior.
- Keep endpoints under `/api/application/users/{user:id}/api-keys`.
- Create responses must expose `meta.secret_token` only once.
- List responses must not expose token secrets.

---

## File Structure

- Create `app/Http/Controllers/Api/Application/Users/ApiKeyController.php`: Application user API key endpoint logic.
- Create `app/Http/Requests/Api/Application/Users/GetUserApiKeysRequest.php`: READ permission request.
- Create `app/Http/Requests/Api/Application/Users/StoreUserApiKeyRequest.php`: WRITE permission request and validation.
- Create `app/Http/Requests/Api/Application/Users/DeleteUserApiKeyRequest.php`: WRITE permission request.
- Create `app/Transformers/Api/Application/ApiKeyTransformer.php`: safe API key response fields.
- Modify `routes/api-application.php`: register nested user API key routes.
- Create `tests/Integration/Api/Application/Users/ApiKeyControllerTest.php`: integration coverage.

## Task 1: Tests First

**Files:**
- Create: `tests/Integration/Api/Application/Users/ApiKeyControllerTest.php`

**Interfaces:**
- Consumes: existing `ApplicationApiIntegrationTestCase`.
- Produces: failing expectations for `GET`, `POST`, and `DELETE`.

- [ ] **Step 1: Write failing integration tests**

Create tests for:

```php
public function test_api_key_can_be_created_for_user(): void
public function test_api_keys_are_returned_without_secret_token(): void
public function test_api_key_can_be_deleted_for_user(): void
public function test_api_key_belonging_to_another_user_cannot_be_deleted(): void
public function test_api_key_validation_errors_are_returned(): void
public function test_api_key_without_write_permissions_cannot_create_or_delete_keys(): void
```

- [ ] **Step 2: Run focused tests and verify RED**

Run:

```bash
vendor/bin/pest tests/Integration/Api/Application/Users/ApiKeyControllerTest.php
```

Expected: route/controller missing failures before implementation.

## Task 2: Application API Endpoint Implementation

**Files:**
- Create: `app/Http/Controllers/Api/Application/Users/ApiKeyController.php`
- Create: `app/Http/Requests/Api/Application/Users/GetUserApiKeysRequest.php`
- Create: `app/Http/Requests/Api/Application/Users/StoreUserApiKeyRequest.php`
- Create: `app/Http/Requests/Api/Application/Users/DeleteUserApiKeyRequest.php`
- Create: `app/Transformers/Api/Application/ApiKeyTransformer.php`
- Modify: `routes/api-application.php`

**Interfaces:**
- Consumes: `User::createToken(?string $memo, ?array $ips)`.
- Produces:
  - `ApiKeyController::index(GetUserApiKeysRequest $request, User $user): array`
  - `ApiKeyController::store(StoreUserApiKeyRequest $request, User $user): array`
  - `ApiKeyController::delete(DeleteUserApiKeyRequest $request, User $user, string $identifier): JsonResponse`

- [ ] **Step 1: Add request classes**

`GetUserApiKeysRequest` uses `User::RESOURCE_NAME` with `AdminAcl::READ`.

`StoreUserApiKeyRequest` uses `User::RESOURCE_NAME` with `AdminAcl::WRITE`, reuses `ApiKey::getRules()` for `description` and `allowed_ips`, and validates each IP with `IPTools\Range::parse($ip)->valid()`.

`DeleteUserApiKeyRequest` uses `User::RESOURCE_NAME` with `AdminAcl::WRITE`.

- [ ] **Step 2: Add transformer**

`ApiKeyTransformer` returns `identifier`, `description`, `allowed_ips`, `last_used_at`, and `created_at`.

- [ ] **Step 3: Add controller**

`store` checks the target user's `apiKeys` count against `config('panel.api.key_limit')`, calls `$user->createToken(...)`, logs `user:api-key.create`, and returns Fractal item with `meta.secret_token`.

`index` transforms `$user->apiKeys`.

`delete` scopes by `$user->apiKeys()->where('identifier', $identifier)->firstOrFail()`, logs `user:api-key.delete`, deletes the key, and returns `{}` with HTTP 200.

- [ ] **Step 4: Register routes**

Add nested routes inside `Route::prefix('/users')`:

```php
Route::prefix('/{user:id}/api-keys')->group(function () {
    Route::get('/', [Application\Users\ApiKeyController::class, 'index']);
    Route::post('/', [Application\Users\ApiKeyController::class, 'store']);
    Route::delete('/{identifier}', [Application\Users\ApiKeyController::class, 'delete']);
});
```

- [ ] **Step 5: Run focused tests and verify GREEN**

Run:

```bash
vendor/bin/pest tests/Integration/Api/Application/Users/ApiKeyControllerTest.php
```

Expected: all tests in the new file pass.

## Task 3: Verification

**Files:**
- All changed PHP files.

- [ ] **Step 1: Format changed PHP files**

Run:

```bash
vendor/bin/pint app/Http/Controllers/Api/Application/Users/ApiKeyController.php app/Http/Requests/Api/Application/Users/GetUserApiKeysRequest.php app/Http/Requests/Api/Application/Users/StoreUserApiKeyRequest.php app/Http/Requests/Api/Application/Users/DeleteUserApiKeyRequest.php app/Transformers/Api/Application/ApiKeyTransformer.php routes/api-application.php tests/Integration/Api/Application/Users/ApiKeyControllerTest.php
```

- [ ] **Step 2: Run focused tests**

Run:

```bash
vendor/bin/pest tests/Integration/Api/Application/Users/ApiKeyControllerTest.php tests/Integration/Api/Client/ApiKeyControllerTest.php
```

- [ ] **Step 3: Commit and push branch**

Run:

```bash
git add app/Http/Controllers/Api/Application/Users/ApiKeyController.php app/Http/Requests/Api/Application/Users/GetUserApiKeysRequest.php app/Http/Requests/Api/Application/Users/StoreUserApiKeyRequest.php app/Http/Requests/Api/Application/Users/DeleteUserApiKeyRequest.php app/Transformers/Api/Application/ApiKeyTransformer.php routes/api-application.php tests/Integration/Api/Application/Users/ApiKeyControllerTest.php docs/superpowers/specs/2026-07-13-application-user-api-keys-design.md docs/superpowers/plans/2026-07-13-application-user-api-keys.md
git commit -m "feat: add application user api key endpoints"
git push -u origin feature/application-user-api-keys
```

## Self-Review

- Spec coverage: create, list, delete, validation, ACL, and response shape are covered.
- Placeholder scan: no placeholders remain.
- Type consistency: controller and request signatures match the existing Laravel route model binding style.
