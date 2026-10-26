@extends('layouts.front')

@section('title', 'Page introuvable — ' . config('app.name'))

@section('content')
    <section class="mx-auto flex min-h-[80svh] max-w-7xl flex-col justify-center px-5 pb-20 pt-40 sm:px-8 lg:px-12">
        <p data-hero class="label-mono mb-4">Erreur 404</p>
        <h1 data-hero class="font-serif text-6xl leading-none text-paper sm:text-8xl">
            Page <em class="text-brass">introuvable.</em>
        </h1>
        <p data-hero class="mt-6 max-w-md text-lg text-paper-dim">
            Cette page n'existe pas ou a été déplacée.
        </p>
        <div data-hero class="mt-10 flex flex-wrap gap-4">
            <a href="{{ route('home') }}" class="btn-primary"><x-icon name="arrow-left" /> Retour à l'accueil</a>
            <a href="{{ route('projects') }}" class="btn-ghost">Voir les projets</a>
        </div>
    </section>
@endsection
