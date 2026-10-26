# Mon Portfolio

Portfolio de développeur personnel, construit avec Laravel 12, Tailwind CSS et Vite. Gère les projets, compétences et informations de contact via un espace d'administration.

## Stack

-   **Backend** : Laravel 12 (PHP 8.2+)
-   **Frontend** : Blade + Tailwind CSS 3 + Alpine.js, compilé via Vite
-   **Animations** : GSAP (révélations au scroll) et Three.js (sphère de particules du hero, chargée uniquement sur l'accueil)
-   **Base de données** : SQLite en local (configurable via `.env`)
-   **Authentification** : Laravel Breeze (connexion uniquement, inscription publique désactivée)

## Installation

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed
php artisan db:seed --class=SkillSeeder   # compétences de départ (facultatif)
php artisan storage:link

npm run build   # ou npm run dev en développement
```

Un compte admin est créé par le seeder (`admin@example.com` / `password`) — connecte-toi sur `/login` puis change immédiatement le mot de passe et l'email depuis `/admin`.

## Développement

```bash
composer dev
```

Lance en parallèle le serveur PHP, la file d'attente, les logs (`pail`) et Vite.

## Structure

-   `/` , `/projects`, `/projects/{slug}`, `/about`, `/contact` — pages publiques
-   `/sitemap.xml` — plan du site pour les moteurs de recherche
-   `/admin/*` (protégé par authentification) — gestion des projets, catégories, compétences et paramètres du site

## Personnalisation

1. `APP_NAME` dans `.env` — nom affiché sur le site
2. `/admin/settings` — bio, liens sociaux, CV, texte de la page d'accueil
3. `/admin/projects`, `/admin/categories`, `/admin/skills` — contenu du portfolio
4. Pour chaque projet : une image (capture d'écran, idéalement 1600×1000), et si possible une vidéo YouTube/Vimeo de démo
5. `GTM_ID` ou `GA_ID` dans `.env` — mesure d'audience (facultatif). Rien n'est chargé tant que le visiteur n'a pas accepté le bandeau cookies.

## Tests

```bash
php artisan test
```

## Publier sur GitHub Pages (version statique)

GitHub Pages n'exécute pas PHP : on y publie une copie statique du site, générée depuis ta version locale.

```bash
# 1. Ajouter ou modifier les projets dans l'admin en local (http://127.0.0.1:8000/admin)
# 2. Compiler les assets et générer le site dans docs/
npm run build
php artisan portfolio:export
# 3. Publier
git add -A
git commit -m "Mise à jour du site"
git push
```

Le site est servi sur `STATIC_SITE_URL` (par défaut https://luc-arthur.github.io/portfolio/).
Réglage à faire une seule fois sur GitHub : **Settings → Pages → Build and deployment → Deploy from a branch → `main` / `/docs`**.

Dans la version statique :
-   les images sont converties en JPEG allégés (1600 px de large maximum) ;
-   le bouton CV pointe directement vers le PDF ;
-   le formulaire de contact passe par [Formspree](https://formspree.io) si `FORMSPREE_ID` est renseigné, sinon il est remplacé par un bouton e-mail ;
-   l'admin n'existe pas : tout se modifie en local, puis on régénère.

Ne modifie pas `docs/` à la main : la commande efface et recrée ce dossier à chaque export.

## Mise en ligne sur un hébergeur PHP : checklist

-   [ ] Changer l'email et le mot de passe du compte admin (`/profile`)
-   [ ] `.env` de production : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://ton-domaine`
-   [ ] Configurer un vrai serveur mail (`MAIL_MAILER`, `MAIL_HOST`…), sinon les messages du formulaire de contact ne partent pas
-   [ ] `php artisan migrate --force`, `php artisan storage:link`, `npm run build`
-   [ ] `php artisan optimize` (mise en cache de la config, des routes et des vues)
-   [ ] Déclarer `https://ton-domaine/sitemap.xml` dans Google Search Console
-   [ ] Ne jamais publier le fichier `.env`
