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

## Mise en ligne : checklist

-   [ ] Changer l'email et le mot de passe du compte admin (`/profile`)
-   [ ] `.env` de production : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://ton-domaine`
-   [ ] Configurer un vrai serveur mail (`MAIL_MAILER`, `MAIL_HOST`…), sinon les messages du formulaire de contact ne partent pas
-   [ ] `php artisan migrate --force`, `php artisan storage:link`, `npm run build`
-   [ ] `php artisan optimize` (mise en cache de la config, des routes et des vues)
-   [ ] Déclarer `https://ton-domaine/sitemap.xml` dans Google Search Console
-   [ ] Ne jamais publier le fichier `.env`
