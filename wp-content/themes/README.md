# wp-content/themes

Un tema por cliente/prototipo, un subdirectorio por tema — igual que
WordPress los espera normalmente:

```
wp-content/themes/
├── clientea/
│   └── style.css   (cabecera con Theme Name, etc.)
├── clienteb/
│   └── ...
```

Esta carpeta se monta como bind mount en el contenedor
(`docker-compose.yaml`), así que cualquier tema que agregues aquí y subas al
repo queda disponible en el contenedor la próxima vez que hagas
`docker compose up -d` (o al reiniciarlo, si ya estaba corriendo) — no hace
falta subirlo a mano por SFTP ni por el uploader de wp-admin.

El resto de `wp-content` (uploads, plugins, cache, etc.) sigue viviendo en
el volumen nombrado `wordpress_multisite_data`, no en el repo.

Después, en `wp-admin` → **Apariencia → Temas** dentro del subsitio del
cliente correspondiente, activa el tema que le toca (ver README.md raíz,
paso 8).
