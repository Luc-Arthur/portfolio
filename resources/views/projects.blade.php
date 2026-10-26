@extends('layouts.front')

@section('title', 'Projets — ' . config('app.name'))
@section('description', 'Sélection de projets web réalisés par ' . config('app.name') . ' : applications, sites et outils.')

@section('content')
    {{-- Le filtre se fait dans le navigateur : il fonctionne aussi dans la version statique du site --}}
    <section x-data="{
        category: @js((string) $selectedCategory),
        init() {
            this.category = new URLSearchParams(location.search).get('category') ?? this.category;
        },
        select(id) {
            this.category = id;
            const url = new URL(location.href);
            id ? url.searchParams.set('category', id) : url.searchParams.delete('category');
            history.replaceState(null, '', url);
        },
    }" class="mx-auto max-w-7xl px-5 pb-28 pt-40 sm:px-8 lg:px-12">
        <header data-hero class="max-w-3xl">
            <p class="label-mono mb-4">Portfolio · {{ $projects->count() }} {{ Str::plural('projet', $projects->count()) }}</p>
            <h1 class="font-serif text-6xl leading-none text-paper sm:text-8xl">Projets<span class="text-brass">.</span></h1>
            <p class="mt-6 text-lg text-paper-dim">
                Applications, sites et outils que j'ai conçus et développés, du prototype à la mise en production.
            </p>
        </header>

        @if ($categories->count() > 1)
            <nav data-hero aria-label="Filtrer par catégorie" class="mt-12 flex flex-wrap gap-2">
                @foreach ([null => 'Tous'] + $categories->pluck('name', 'id')->all() as $id => $name)
                    <a href="{{ route('projects', $id ? ['category' => $id] : []) }}"
                        @click.prevent="select(@js((string) $id))"
                        :aria-current="category === @js((string) $id) ? 'page' : null"
                        :class="category === @js((string) $id) ? 'bg-paper text-ink-950' : 'border border-white/10 text-paper-dim hover:border-white/30 hover:text-paper'"
                        class="rounded-full px-4 py-2 text-sm transition">
                        {{ $name }}
                    </a>
                @endforeach
            </nav>
        @endif

        @if ($projects->isNotEmpty())
            <div data-reveal data-reveal-stagger class="mt-16 grid grid-cols-1 gap-x-10 gap-y-16 md:grid-cols-2">
                @foreach ($projects as $project)
                    <div x-show="!category || category === @js((string) $project->category_id)" x-transition.opacity.duration.400ms>
                        @include('partials.project-card', ['project' => $project, 'index' => $loop->index])
                    </div>
                @endforeach
            </div>
        @else
            <div class="mt-16 rounded-3xl border border-dashed border-white/10 bg-ink-800/40 px-8 py-16 text-center">
                <p class="font-serif text-4xl text-paper">Rien ici pour le moment</p>
                <p class="mx-auto mt-4 max-w-md text-paper-dim">Les projets sont en cours de publication. Revenez bientôt !</p>
            </div>
        @endif
    </section>
@endsection
