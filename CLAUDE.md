# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

Deployment configuration only — a `docker-compose.yaml` + `.env` pair for a
single WordPress **Multisite** (subdomain mode) instance, meant to be cloned
onto a VPS and run with `docker compose`. There is no application code, no
build step, no linter, and no test suite; the entire repo is the compose
file, an env template, and the README documenting the deploy procedure.

## Commands

```bash
cp .env.example .env        # fill in real values before first run; .env is gitignored
docker compose up -d        # (re)create the wordpress-multisite container
docker compose config       # validate/render the compose file, e.g. after editing env vars
docker exec -it mysql_db mysql -uroot -p   # create the DB/user this stack expects (one-time, on the shared db container)
```

There's no local dev loop — changes here are edited and then applied by
deploying to the target VPS per the README; there's nothing to build or test
in isolation.

## Architecture

- **Single container, shared infrastructure.** `wordpress-multisite`
  (`wordpress:php8.3-apache`) is the only service defined here. It does
  *not* run its own database or reverse proxy — it depends on two things
  that already exist on the host and are *not* part of this repo:
  - a MySQL container named `mysql_db` (alias `db`) shared across this
    VPS's other WordPress deployments,
  - an external Docker network named `npm` reaching Nginx Proxy Manager,
    which terminates TLS (wildcard cert via DNS-01) and reverse-proxies by
    container name/port 80. No ports are published to the host.
- **Two-phase `wp-config.php` activation.** `WORDPRESS_CONFIG_EXTRA` in
  `docker-compose.yaml` contains the multisite `define()` block with most
  lines *commented out on purpose*. Phase 1: deploy with only
  `WP_ALLOW_MULTISITE` active, complete the normal WordPress installer, then
  run the "Network Setup" wizard in wp-admin. Phase 2: uncomment the
  `MULTISITE` / `SUBDOMAIN_INSTALL` / `DOMAIN_CURRENT_SITE` /
  `PATH_CURRENT_SITE` / `SITE_ID_CURRENT_SITE` / `BLOG_ID_CURRENT_SITE`
  lines (matching what the wizard displays — verify, don't assume) and
  re-run `docker compose up -d`. Enabling multisite before the base install
  exists breaks the site — don't reorder these steps.
- **One install, many client subsites.** Multisite is used as a
  theme-prototyping tool: each client/prototype gets its own subdomain
  subsite (`clienteA.tudominio.com`, ...) under one shared network, sharing
  the same seed content. Switching what a client sees is done by activating
  a different theme *within their subsite*, not by spinning up new
  containers. A prototype needing very different plugins/config is expected
  to graduate to its own standalone container instead of living in this
  network.
- **Themes are version-controlled, everything else in `wp-content` isn't.**
  `wp-content/themes/` in this repo is bind-mounted into the container
  (`docker-compose.yaml`), one subdirectory per client/theme — commit a
  theme here and it's live on the next `docker compose up -d`/restart, no
  manual upload needed. Uploads, plugins, cache, etc. remain in the named
  volume `wordpress_multisite_data` and are not tracked in git.
- **Env/config split.** `docker-compose.yaml` is versioned and has no
  secrets. `.env` (from `.env.example`) holds `WORDPRESS_DOMAIN`,
  `WORDPRESS_DB_USER`, `WORDPRESS_DB_PASSWORD`, `WORDPRESS_DB_NAME` and is
  gitignored — it's created directly on the target VPS, never committed.
  The DB/user in `.env` must be created manually on the shared `mysql_db`
  container beforehand (README step 2) with matching names/password.
