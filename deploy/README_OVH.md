# Deploiement OVH Web Cloud - La Main du Sellier

Ce projet est heberge sur **OVH Web Cloud (mutualise)**.
Le code source reste sur **GitHub**.

## Architecture

- GitHub = source de verite du code
- GitHub Actions = pipeline de deploiement
- OVH Web Cloud = hebergement public du site

## Donnees OVH utilisees

- Serveur SFTP: `ftp.cluster121.hosting.ovh.net`
- Port SFTP: `22`
- Utilisateur: `lamainn`
- Home: `/home/lamainn`
- Repertoire web public: `www` (dossier relatif au home SFTP)

## Secret GitHub obligatoire

Dans le repo `Lidhak/lamaindusellier`:

- `Settings` -> `Secrets and variables` -> `Actions` -> `New repository secret`
- Ajouter: `OVH_SFTP_PASSWORD` = mot de passe FTP/SFTP OVH

## Pipeline

- CI: `.github/workflows/ci.yml`
- Deploy: `.github/workflows/deploy-ovh.yml`

Flux:
1. Push sur `main`
2. CI passe
3. Workflow deploy envoie `index.html`, `contact.php` et `assets/` vers `www` via SFTP
4. Le site est en ligne sur `lamaindusellier.fr`

## Important (remplacement WordPress)

Le deploy utilise une synchronisation avec `--delete` sur `/www`.

- Les fichiers existants WordPress non presents dans ce repo seront supprimes.
- C'est le comportement voulu pour basculer vers le site vitrine statique.

## Test rapide local

```bash
cd /Users/khalidmokhtari/lamaindusellier
python3 -m http.server 8080
```

Ouvrir `http://localhost:8080`.
