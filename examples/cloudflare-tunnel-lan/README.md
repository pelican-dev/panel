# Pelican Panel + Cloudflare Tunnel + LAN HTTPS

This variant extends the base Cloudflare Tunnel example by also exposing the Panel over HTTPS on your LAN IP. Cloudflared keeps the service private to the internet, while LAN devices can connect straight to the host when they are on the same network.

## What You Get

- `docker-compose.yml` that adds a bound 443/tcp listener for LAN clients while keeping the Cloudflare tunnel flow untouched
- `Caddyfile` that continues to serve the tunnel on plain HTTP and serves the same application over HTTPS with an internal CA for LAN traffic
- `.env.example` with the additional `LAN_BIND_ADDRESS` helper so you can decide which host interface should accept LAN HTTPS connections

## Important Notes About APP_URL

Pelican only serves requests for the canonical `APP_URL`. To keep authentication and signed URLs working, continue to visit the Panel using that hostname, even on the LAN. Achieve the “local IP” requirement by pointing that hostname at the server’s private IP when you are on-site (split-horizon DNS, router override, or a hosts file entry). When you are away, let public DNS resolve the hostname back to Cloudflare so the tunnel handles traffic.

## Usage

1. Copy this directory somewhere outside of the repository, rename `.env.example` to `.env`, and populate the variables.
2. Set `PANEL_DOMAIN`, `APP_URL`, and `ADMIN_EMAIL` as in the base example, then set `LAN_BIND_ADDRESS` to the LAN IP you want Docker to bind on (for example `192.168.1.50`).
3. Create your Cloudflare Tunnel token and assign your hostname to `panel:80` exactly like the base example.
4. Start the stack with `docker compose up -d` and watch logs via `docker compose logs -f cloudflared panel`.
5. On first LAN connection, Caddy issues an internal CA certificate. Export it with `docker compose cp pelican-panel:/data/caddy/pki/authorities/local/root.crt ./pelican-panel-root.crt` and trust it on devices so the browser accepts the LAN HTTPS session.
6. Configure your local DNS/hosts so the `APP_URL` hostname resolves to the LAN IP when you are on-site. Leave public DNS pointing at Cloudflare for remote access.

Once trusted, LAN traffic stays on your network over HTTPS while remote traffic continues to flow through Cloudflare Zero Trust.
