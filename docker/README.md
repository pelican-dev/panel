
# Pelican Panel - Docker Image

## Requirements

- Make sure Docker is installed on your system. You can follow the official Docker installation guide here: [Docker Installation](https://docs.docker.com/engine/install/).

## Setup

1. Create a directory for the Pelican Panel:

```bash
mkdir -p /opt/pelican-panel
cd /opt/pelican-panel
```

2. Download the necessary files (docker-compose.yml and example.env):

```bash
curl -L -o docker-compose.yml https://raw.githubusercontent.com/pelican-dev/panel/refs/heads/main/docker/docker-compose.yml
curl -L -o .env https://raw.githubusercontent.com/pelican-dev/panel/refs/heads/main/docker/example.env
```

3. Edit the `.env` file to adjust the required settings:

```bash
nano .env
```

4. Start the Docker container:

```bash
docker compose up -d
```

5. Create the first user for the panel:

```bash
docker compose exec panel php artisan p:user:make
```

## Debugging

To view the container logs, first navigate to the directory where the Docker Compose file is located:

```bash
cd /opt/pelican-panel
```

Then, you can view the logs in real-time using the following command:

```bash
docker compose logs -f
```

## Environment Variables

There are several environment variables that you can configure for the panel. You can either provide your own `.env` file or modify the default one. Below is a table of the available options:

**Note:** If your `APP_URL` uses `https://`, you will need to provide an `LE_EMAIL` for Let's Encrypt certificate generation.

| Variable          | Description                                                                   | Required |
|-------------------|-------------------------------------------------------------------------------|----------|
| `APP_URL`         | The URL where the panel will be accessible (including protocol)               | Yes      |
| `APP_TIMEZONE`    | The timezone to use for the panel                                             | Yes      |
| `LE_EMAIL`        | The email used for Let's Encrypt certificate generation                       | Yes      |
| `DB_HOST`         | The host of the MySQL instance                                                | Yes      |
| `DB_PORT`         | The port of the MySQL instance                                                | Yes      |
| `DB_DATABASE`     | The name of the MySQL database                                                | Yes      |
| `DB_USERNAME`     | The MySQL user                                                                | Yes      |
| `DB_PASSWORD`     | The password for the specified MySQL user                                     | Yes      |
| `CACHE_STORE`     | The cache driver (see [Cache Drivers](#cache-drivers) for details)            | Yes      |
| `SESSION_DRIVER`  | The session driver to use (refer to Laravel documentation for details)        | Yes      |
| `QUEUE_DRIVER`    | The queue driver to use (refer to Laravel documentation for details)          | Yes      |
| `REDIS_HOST`      | The hostname or IP address of the Redis database                              | Yes      |
| `REDIS_PASSWORD`  | The password for securing the Redis database                                  | Optional |
| `REDIS_PORT`      | The port the Redis database is using on the host                              | Optional |
| `MAIL_DRIVER`     | The email driver to use (see [Mail Drivers](#mail-drivers) for details)       | Yes      |
| `MAIL_FROM`       | The email address used as the sender email                                    | Yes      |
| `MAIL_HOST`       | The host of your mail driver instance                                         | Optional |
| `MAIL_PORT`       | The port of your mail driver instance                                         | Optional |
| `MAIL_USERNAME`   | The username for your mail driver                                             | Optional |
| `MAIL_PASSWORD`   | The password for your mail driver                                             | Optional |

### Cache Drivers

You can choose from various cache drivers, depending on your preference. If you're using Docker, we recommend Redis as it can be easily run in a container.

| Driver   | Description                          | Required Variables                                 |
|----------|--------------------------------------|----------------------------------------------------|
| `redis`  | Host where Redis is running          | `REDIS_HOST`                                       |
| `redis`  | Port Redis is running on             | `REDIS_PORT`                                       |
| `redis`  | Redis database password              | `REDIS_PASSWORD`                                   |

### Mail Drivers

Choose a mail driver based on your needs. Below are the available options:

| Driver     | Description                          | Required Variables                                                        |
|------------|--------------------------------------|---------------------------------------------------------------------------|
| `mail`     | Uses the built-in PHP mail function  | `MAIL_FROM`                                                               |
| `mandrill` | [Mandrill](http://www.mandrill.com/) | `MAIL_FROM`, `MAIL_USERNAME`                                              |
| `postmark` | [Postmark](https://postmarkapp.com/) | `MAIL_FROM`, `MAIL_USERNAME`                                              |
| `mailgun`  | [Mailgun](https://www.mailgun.com/)  | `MAIL_FROM`, `MAIL_USERNAME`, `MAIL_HOST`                                 |
| `smtp`     | Any SMTP server configuration        | `MAIL_FROM`, `MAIL_USERNAME`, `MAIL_HOST`, `MAIL_PASSWORD`, `MAIL_PORT`   |
