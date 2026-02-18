# Deploiement OVH - La Main du Sellier

Ce projet est heberge sur OVH.
Le code source reste sur GitHub.

## Objectif

- GitHub = source unique du code.
- OVH = hebergement web (Nginx) pour `lamaindusellier.fr`.
- Chaque push sur `main` declenche un deploy automatique vers OVH.

## 1) Premiere installation sur le serveur OVH (remplacement WordPress)

> Cette procedure ecrase la config web existante pour ce domaine.

```bash
sudo apt update
sudo apt install -y nginx git

sudo mkdir -p /var/www
cd /var/www

# Si un ancien dossier WordPress existe pour ce domaine, le sauvegarder puis le retirer
if [ -d /var/www/lamaindusellier ]; then
  sudo mv /var/www/lamaindusellier /var/www/lamaindusellier.backup.$(date +%F-%H%M%S)
fi

sudo git clone https://github.com/Lidhak/lamaindusellier.git /var/www/lamaindusellier
sudo chown -R $USER:$USER /var/www/lamaindusellier
```

## 2) Nginx

```bash
sudo cp /var/www/lamaindusellier/deploy/nginx.lamaindusellier.conf /etc/nginx/sites-available/lamaindusellier
sudo ln -sf /etc/nginx/sites-available/lamaindusellier /etc/nginx/sites-enabled/lamaindusellier

# Optionnel: retirer un ancien vhost WordPress associe au domaine
# sudo rm -f /etc/nginx/sites-enabled/wordpress

sudo nginx -t
sudo systemctl reload nginx
```

## 3) SSL

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d lamaindusellier.fr -d www.lamaindusellier.fr
```

## 4) Secrets GitHub Actions a configurer

Dans le repo GitHub `Lidhak/lamaindusellier`, ajouter:

- `OVH_HOST` (IP ou hostname du serveur)
- `OVH_USER` (utilisateur SSH)
- `OVH_SSH_PORT` (optionnel, sinon 22)
- `OVH_APP_DIR` (optionnel, sinon `/var/www/lamaindusellier`)
- `OVH_SSH_PRIVATE_KEY` (cle privee complete)

## 5) Pipeline automatique

- Workflow CI: `.github/workflows/ci.yml`
- Workflow deploy OVH: `.github/workflows/deploy-ovh.yml`
- Script serveur: `deploy/ovh-deploy.sh`

Flux:
1. Push sur `main`
2. CI OK
3. Deploy OVH automatique
4. Nginx sert la nouvelle version
