# Application User API Keys Design

## Goal

Add Application API endpoints that allow an authorized application API key to create, list, and delete client account API keys for a specific Pelican user.

## API

- `POST /api/application/users/{user:id}/api-keys`
- `GET /api/application/users/{user:id}/api-keys`
- `DELETE /api/application/users/{user:id}/api-keys/{identifier}`

`POST` accepts:

```json
{
  "description": "Mado Hosting Control Panel",
  "allowed_ips": []
}
```

`POST` returns the one-time secret in Pelican's existing API key shape:

```json
{
  "object": "api_key",
  "attributes": {
    "identifier": "pacc_xxxxxxxxxxx"
  },
  "meta": {
    "secret_token": "yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy"
  }
}
```

The full client API key is `attributes.identifier + meta.secret_token`.

## Architecture

The Application API gets a focused `Users\ApiKeyController`. It reuses `User::createToken()` so the generated keys stay normal client account keys (`ApiKey::TYPE_ACCOUNT`) and keep the same identifier, token, allowed IP, and storage behavior as `/api/client/account/api-keys`.

Application request classes enforce the existing Application API ACL model:

- `READ` on `User::RESOURCE_NAME` for listing.
- `WRITE` on `User::RESOURCE_NAME` for creation and deletion.

The Application API gets its own `ApiKeyTransformer` with the same safe public fields as the Client API transformer. `secret_token` is only added by the create response.

## Validation And Errors

Creation validates `description` and `allowed_ips` using the same rules as the Client API endpoint:

- `description` is required, nullable by model rules, string, and at most 500 characters.
- `allowed_ips` is an array with at most 50 entries.
- Each allowed IP must be a valid IP address or CIDR range.

Creation respects `config('panel.api.key_limit')` for the target user's account keys and returns the existing `DisplayException` message when the limit is reached.

Deletion only targets account keys owned by the route user, returns an empty JSON object (`{}`) on success, and returns `404` when the identifier belongs to another user or to an application API key.

## Testing

Integration tests cover:

- Create response format and database persistence for the target user.
- List response without `meta.secret_token`.
- Delete behavior scoped to the target user and account key type.
- Permission denial for application API keys that only have `READ` when calling write endpoints.
- Validation of invalid `description` and `allowed_ips`.

## Constraints

- Do not introduce new dependencies.
- Do not alter existing Client API behavior.
- Keep the endpoint under the existing `/api/application/users` route group.
- Use Pelican's existing Fractal response format.
