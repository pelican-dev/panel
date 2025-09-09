<img width="20%" src="https://raw.githubusercontent.com/pelican-dev/panel/main/public/pelican.svg" alt="logo">

# Pelican Panel

![Total Downloads](https://img.shields.io/github/downloads/pelican-dev/panel/total?style=flat&label=Total%20Downloads&labelColor=rgba(0%2C%2070%2C%20114%2C%201)&color=rgba(255%2C%20255%2C%20255%2C%201)) 
![Latest Release](https://img.shields.io/github/v/release/pelican-dev/panel?style=flat&label=Latest%20Release&labelColor=rgba(0%2C%2070%2C%20114%2C%201)&color=rgba(255%2C%20255%2C%20255%2C%201))  

Pelican Panel is an open-source, web-based application designed for easy management of game servers.
It offers a user-friendly interface for deploying, configuring, and managing servers, with features like real-time resource monitoring, Docker container isolation, and extensive customization options.
Ideal for both individual gamers and hosting companies, it simplifies server administration without requiring deep technical knowledge.

Fly High, Game On: Pelican's pledge for unrivaled game servers.

## Links

* [Website](https://pelican.dev)
* [Docs](https://pelican.dev/docs)
* [Discord](https://discord.gg/pelican-panel)
* [Wings](https://github.com/pelican-dev/wings)

## Supported Games and Servers

Pelican supports a wide variety of games by utilizing Docker containers to isolate each instance.
This gives you the power to run game servers without bloating machines with a host of additional dependencies.

Some of our popular eggs include:

| Category                                                             | Eggs            |               |                    |                |
|----------------------------------------------------------------------|-----------------|---------------|--------------------|----------------|
| [Minecraft](https://github.com/pelican-eggs/minecraft)               | Paper           | Sponge        | Bungeecord         | Waterfall      |
| [SteamCMD](https://github.com/pelican-eggs/steamcmd)                 | 7 Days to Die   | ARK: Survival | Arma 3             | Counter Strike |
|                                                                      | DayZ            | Enshrouded    | Left 4 Dead        | Palworld       |
|                                                                      | Project Zomboid | Satisfactory  | Sons of the Forest | Starbound      |
| [Standalone Games](https://github.com/pelican-eggs/games-standalone) | Among Us        | Factorio      | FTL                | GTA            |
|                                                                      | Kerbal Space    | Mindustry     | Rimworld           | Terraria       |
| [Discord Bots](https://github.com/pelican-eggs/chatbots)             | Redbot          | JMusicBot     | Dynamica           |                |
| [Voice Servers](https://github.com/pelican-eggs/voice)               | Mumble          | Teamspeak     | Lavalink           |                |
| [Software](https://github.com/pelican-eggs/software)                 | Elasticsearch   | Gitea         | Grafana            | RabbitMQ       |
| [Programming](https://github.com/pelican-eggs/generic)               | Node.js         | Python        | Java               | C#             |
| [Databases](https://github.com/pelican-eggs/database)                | Redis           | MariaDB       | PostgreSQL         | MongoDB        |
| [Storage](https://github.com/pelican-eggs/storage)                   | S3              | SFTP Share    |                    |                |
| [Monitoring](https://github.com/pelican-eggs/monitoring)             | Prometheus      | Loki          |                    |                |

## Running Behind an HTTPS Reverse Proxy

When deploying Pelican Panel behind an HTTPS reverse proxy (nginx, Apache, Cloudflare, etc.), you need to configure both the panel and your proxy server to properly handle HTTPS requests and avoid mixed content issues.

### Panel Configuration

Update your `.env` file with the following settings:

```env
APP_URL=https://your-domain.com
TRUST_PROXIES=*
SESSION_SECURE_COOKIE=true
```

- `APP_URL`: Set to your public HTTPS URL
- `TRUST_PROXIES`: Set to `*` to trust all proxies, or specify your proxy's IP addresses (comma-separated)
- `SESSION_SECURE_COOKIE`: Ensures session cookies are only sent over HTTPS

### Nginx Configuration Example

```nginx
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;

    # SSL configuration
    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;

    location / {
        proxy_pass http://127.0.0.1:80;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;
    }
}
```

The critical headers are `X-Forwarded-Proto` and `X-Forwarded-Host`, which tell the panel that the original request was made over HTTPS.

After updating your configuration, clear the application cache:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Repository Activity
![Stats](https://repobeats.axiom.co/api/embed/4d8cc7012b325141e6fae9c34a22b3669ad5753b.svg "Repobeats analytics image")

*Copyright Pelican® 2024-2025*
