# La Main du Sellier

Site vitrine pour l'atelier **La Main du Sellier**.

## Positionnement

Restauration artisanale de sellerie:

- automobile (collection et moderne)
- moto (touring, custom, sport)
- finitions sur mesure (cuir, alcantara, surpiqures)

## Stack

- HTML
- CSS
- JavaScript (vanilla)

## Lancer localement

```bash
cd /Users/khalidmokhtari/lamaindusellier
python3 -m http.server 8080
```

Puis ouvrir `http://localhost:8080`.

## Deploy OVH (GitHub -> OVH)

Le code reste sur GitHub.
Le site est heberge sur OVH.

- CI: `.github/workflows/ci.yml`
- Deploy: `.github/workflows/deploy-ovh.yml`
- Setup serveur: `deploy/README_OVH.md`

## Domaine

- `https://lamaindusellier.fr`
- `https://www.lamaindusellier.fr`
