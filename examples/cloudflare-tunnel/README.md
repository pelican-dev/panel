# Pelican Panel + Cloudflare Tunnel Example

This example shows how to run Pelican Panel entirely behind a Cloudflare Tunnel so you can keep every port on the host closed while still serving traffic over HTTPS.

## What You Get

- `docker-compose.yml` with Pelican Panel and `cloudflared` on an isolated Docker network
- `Caddyfile` tuned for Pelican Panel behind a reverse proxy, including higher upload limits
- `.env.example` documenting the environment variables that the stack expects

## Usage

1. Copy this directory somewhere outside of the repository, then rename `.env.example` to `.env` and fill in the variables.
2. Update `APP_URL` and `ADMIN_EMAIL` in the `.env` file to match your domain and administrator address.
3. Create a Cloudflare Tunnel in Zero Trust, copy the tunnel token, and paste it into `CLOUDFLARE_TUNNEL_TOKEN`.
4. Configure the tunnel to route your hostname (for example `panel.yourdomain.com`) to the internal service `panel:80`.
5. Start the stack with `docker compose up -d`.
6. Watch the logs with `docker compose logs -f cloudflared panel` and proceed through the Pelican installer once the services are healthy.

Detailed guidance and troubleshooting steps in the docs at `docs/panel/advanced/cloudflare-tunnel`.
