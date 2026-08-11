# WordPress Multisitio — Prototipos de temas

Una sola instalación de WordPress (Multisite, modo **subdominios**) para mostrar
prototipos de temas a distintos clientes. Cada cliente/tema vive en su propio
subsitio (`clienteA.tudominio.com`, `clienteB.tudominio.com`, ...), compartiendo
el mismo contenido/datos de prueba.

Sigue el mismo patrón que ya usas en `peajemexico`: **no se expone ningún
puerto al host**; Nginx Proxy Manager llega al contenedor por nombre, dentro
de la red `npm`.

## Contenido del repo

- `docker-compose.yaml` — servicio `wordpress-multisite`, sin `ports:`.
- `.env.example` — variables a copiar a `.env` (no se versiona).
- `.gitignore` — excluye `.env`.

## Requisitos previos en el VPS

- Redes `npm` y el contenedor `mysql_db` (alias `db`) ya existentes y corriendo
  (los mismos que usan el resto de tus WordPress).
- DNS: un registro **wildcard** `*.tudominio.com` (y el dominio raíz) apuntando
  a la IP del VPS.
- Nginx Proxy Manager con capacidad de emitir certificado **wildcard** — esto
  requiere validación **DNS-01** (por ejemplo con un token de API de
  Cloudflare/tu proveedor DNS), ya que un certificado wildcard no se puede
  validar por HTTP-01.

## Pasos de despliegue

### 1. Traer el repo al VPS

```bash
cd /home/jaco/docker/wordpress
git clone <url-de-tu-repo> wordpress-multisite
cd wordpress-multisite
cp .env.example .env
```

Edita `.env` y rellena `WORDPRESS_DOMAIN`, `WORDPRESS_DB_USER`,
`WORDPRESS_DB_PASSWORD`, `WORDPRESS_DB_NAME` con valores reales (usa una
contraseña fuerte, no la del ejemplo).

### 2. Crear la base de datos y el usuario en el `mysql_db` compartido

```bash
docker exec -it mysql_db mysql -uroot -p
```

```sql
CREATE DATABASE wp_multisite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wp_multisite_user'@'%' IDENTIFIED BY 'la-misma-clave-del-.env';
GRANT ALL PRIVILEGES ON wp_multisite.* TO 'wp_multisite_user'@'%';
FLUSH PRIVILEGES;
```

(los nombres deben coincidir exactamente con los que pusiste en `.env`)

### 3. Levantar el contenedor

```bash
docker compose up -d
```

En este punto `WORDPRESS_CONFIG_EXTRA` solo trae `WP_ALLOW_MULTISITE`
activo; el resto de las líneas de multisitio están comentadas a propósito
(activarlas antes de tiempo rompe el sitio).

### 4. Apuntar NPM al contenedor (antes de instalar)

En NPM → **Proxy Hosts** → *Add Proxy Host*:

- Domain Names: `tudominio.com` y `*.tudominio.com`
- Forward Hostname/IP: `wordpress-multisite` (nombre del contenedor, igual
  que haces con `peajemx-frontend`)
- Forward Port: `80`
- SSL: pide certificado wildcard con reto **DNS**, fuerza HTTPS.

### 5. Instalación normal de WordPress

Entra a `https://tudominio.com`, completa el instalador estándar (título del
sitio, usuario admin, etc.) — este será el **sitio principal** de la red.

### 6. Activar la red multisitio

En `wp-admin` → **Herramientas → Configuración de red**:

- Elige **Subdominios**.
- Dale un título a la red.
- Clic en **Instalar**.

WordPress te va a mostrar un bloque de código para pegar en `wp-config.php`
y en `.htaccess`. El bloque de `wp-config.php` debería coincidir con las
líneas ya comentadas en `docker-compose.yaml` (al ser una red nueva,
`SITE_ID_CURRENT_SITE` y `BLOG_ID_CURRENT_SITE` son `1`). Verifica igual
contra lo que te muestre la pantalla.

### 7. Descomentar la config de multisitio y reiniciar

Edita `docker-compose.yaml`, descomenta las líneas de `MULTISITE`,
`SUBDOMAIN_INSTALL`, `DOMAIN_CURRENT_SITE`, `PATH_CURRENT_SITE`,
`SITE_ID_CURRENT_SITE`, `BLOG_ID_CURRENT_SITE`, y:

```bash
docker compose up -d
```

Esto reinyecta el `wp-config.php` con la red ya activa.

> El `.htaccess` para reglas de multisitio con subdominios normalmente no
> requiere cambios adicionales sobre el que genera WordPress por defecto,
> pero revisa que el bloque que te mostró el asistente esté presente
> (WordPress lo escribe solo si tiene permisos de escritura; si no, cópialo
> a mano dentro del volumen).

### 8. Crear un subsitio por cliente/tema

En `wp-admin` → **Mis Sitios → Red de sitios → Sitios → Añadir nuevo**:

- Dirección del sitio (subdominio): p. ej. `clientea`
- Se genera automáticamente `clientea.tudominio.com`

Sube/activa el tema del prototipo en `Apariencia → Temas` **dentro de ese
subsitio** (o habilítalo a nivel red desde el panel de red si quieres que
esté disponible para todos). Como los temas y plugins se guardan una sola
vez en `wp-content/themes` y `wp-content/plugins`, compartidos por toda la
red, pero cada subsitio activa el que le corresponde — así muestras temas
distintos con el mismo contenido de prueba, sin duplicar instalaciones.

## Notas

- Nada de esto se ejecutó en este VPS: son solo los archivos de arranque.
  El `.env` con las claves reales se crea directamente en el servidor
  destino, nunca se sube al git.
- Si más adelante algún prototipo necesita plugins/config muy distintos al
  resto, ese cliente puntual puede migrar a su propio contenedor
  (mismo patrón que tus otros proyectos WordPress independientes).
- **Límite de subida de la Biblioteca de medios.** `uploads.ini` se monta
  en `/usr/local/etc/php/conf.d/` (ver `docker-compose.yaml`) y sube
  `upload_max_filesize`/`post_max_size` a 64M — ajusta ahí si necesitas
  más. Pero si sigues topando con un límite de ~1MB después de subir esto
  y reiniciar, revisa **Nginx Proxy Manager**: por defecto nginx limita
  `client_max_body_size` a 1M. En el Proxy Host de este sitio, pestaña
  *Advanced*, agrega algo como `client_max_body_size 64M;` en el bloque de
  configuración personalizada (o súbelo en la config global de NPM si
  quieres que aplique a todos los proxy hosts).
