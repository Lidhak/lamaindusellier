# La Main du Sellier

Site vitrine pour l'atelier **La Main du Sellier** (restauration artisanale de sellerie auto/moto).

## Stack

- HTML
- CSS
- JavaScript (vanilla)

## Local

```bash
cd /Users/khalidmokhtari/lamaindusellier
php -S localhost:8080
```

Puis ouvrir `http://localhost:8080`.

Note: le formulaire contact utilise `contact.php` (envoi mail serveur), donc tester en local avec `php -S` plutot que `python3 -m http.server`.

## Deploiement OVH Web Cloud

Le code reste sur GitHub et le site est deploye automatiquement vers OVH via SFTP.

- Workflow CI: `.github/workflows/ci.yml`
- Workflow deploy: `.github/workflows/deploy-ovh.yml`
- Guide OVH: `deploy/README_OVH.md`

Secret requis:

- `OVH_SFTP_PASSWORD`

Domaine cible:

- `https://lamaindusellier.fr`
- `https://www.lamaindusellier.fr`
