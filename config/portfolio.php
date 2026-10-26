<?php

return [

    /*
    | Activé automatiquement par « php artisan portfolio:export » : les pages sont
    | alors générées pour un hébergement statique (GitHub Pages), sans PHP.
    */
    'static' => false,

    // Adresse publique de la version statique du site
    'static_url' => env('STATIC_SITE_URL', 'https://luc-arthur.github.io/portfolio'),

    // Identifiant Formspree (https://formspree.io) pour le formulaire de contact
    // de la version statique. Vide : le formulaire est remplacé par un lien e-mail.
    'formspree_id' => env('FORMSPREE_ID'),

];
