#!/usr/bin/env bash
set -euo pipefail

DOMAIN="${DOMAIN:-lamaindusellier.fr}"
REPO_URL="${REPO_URL:-https://github.com/Lidhak/lamaindusellier.git}"
APP_DIR="${APP_DIR:-/var/www/lamaindusellier}"
NGINX_CONF_NAME="${NGINX_CONF_NAME:-lamaindusellier}"
BACKUP_DIR="/var/backups/lamaindusellier"

if [ "$(id -u)" -ne 0 ]; then
  echo "Run as root: sudo bash deploy/ovh-first-setup.sh"
  exit 1
fi

apt update
apt install -y nginx git

mkdir -p "$BACKUP_DIR"

if [ -d /var/www/html ] && [ "$(ls -A /var/www/html 2>/dev/null)" ]; then
  tar -czf "$BACKUP_DIR/wordpress-html-$(date +%F-%H%M%S).tar.gz" -C /var/www html
fi

if [ -d "$APP_DIR" ]; then
  mv "$APP_DIR" "${APP_DIR}.backup.$(date +%F-%H%M%S)"
fi

git clone "$REPO_URL" "$APP_DIR"
chown -R www-data:www-data "$APP_DIR"

cp "$APP_DIR/deploy/nginx.lamaindusellier.conf" "/etc/nginx/sites-available/$NGINX_CONF_NAME"
ln -sf "/etc/nginx/sites-available/$NGINX_CONF_NAME" "/etc/nginx/sites-enabled/$NGINX_CONF_NAME"

# Disable default/old virtual hosts when migrating from WordPress hosting.
rm -f /etc/nginx/sites-enabled/default

nginx -t
systemctl reload nginx

if command -v certbot >/dev/null 2>&1; then
  certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" --non-interactive --agree-tos -m "contact@$DOMAIN" || true
else
  echo "Install certbot manually for HTTPS: apt install -y certbot python3-certbot-nginx"
fi

echo "First setup complete."
