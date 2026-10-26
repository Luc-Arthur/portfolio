@extends('layouts.front')

@section('title', $project->title . ' — ' . config('app.name'))
@section('description', Str::limit(strip_tags($project->description ?? $project->title), 155))
@section('og_type', 'article')
@if ($project->image)
    @section('og_image', asset('storage/' . $project->image))
@endif

@section('content')
    <article class="mx-auto max-w-7xl px-5 pb-28 pt-36 sm:px-8 lg:px-12">
        <a href="{{ route('projects') }}" data-hero
            class="inline-flex items-center gap-2 text-sm text-paper-dim transition hover:text-paper">
            <x-icon name="arrow-left" /> Tous les projets
        </a>

        <header class="mt-10 grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-8">
                <p data-hero class="label-mono mb-4">
                    {{ $project->category?->name ?? 'Projet' }}
                    @if ($project->date)
                        · {{ $project->date->translatedFormat('F Y') }}
                    @endif
                </p>
                <h1 data-hero class="font-serif text-5xl leading-[0.95] text-paper sm:text-7xl lg:text-8xl">
                    {{ $project->title }}
                </h1>
            </div>

            <div data-hero class="flex flex-wrap gap-3 lg:col-span-4 lg:justify-end">
                @if ($project->demo_url)
                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn-primary">
                        Voir le site <x-icon name="arrow-up-right" />
                    </a>
                @endif
                @if ($project->github_url)
                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn-ghost">
                        <x-icon name="github" /> Code source
                    </a>
                @endif
            </div>
        </header>

        {{-- Visuel principal ; la vidéo ne se charge qu'au clic (plus rapide, sans cookies tiers d'office) --}}
        <div data-hero x-data="{ playing: false }"
            class="relative mt-14 aspect-video overflow-hidden rounded-3xl border border-white/10 bg-ink-800">
            @if ($project->videoEmbedUrl())
                <template x-if="playing">
                    <iframe src="{{ $project->videoEmbedUrl() }}" title="Vidéo de démonstration : {{ $project->title }}"
                        class="absolute inset-0 h-full w-full" allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen></iframe>
                </template>
                <button x-show="!playing" @click="playing = true" class="group absolute inset-0 h-full w-full"
                    aria-label="Lire la vidéo de démonstration">
                    @if ($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="" class="h-full w-full object-cover opacity-70 transition duration-700 group-hover:scale-105 group-hover:opacity-50">
                    @endif
                    <span class="absolute left-1/2 top-1/2 flex h-20 w-20 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-brass text-ink-950 shadow-2xl transition duration-500 group-hover:scale-110">
                        <x-icon name="play" class="ml-1 h-7 w-7" />
                    </span>
                    <span class="label-mono absolute bottom-6 left-1/2 -translate-x-1/2 !text-paper">Voir la démo</span>
                </button>
            @elseif ($project->image)
                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}"
                    class="h-full w-full object-cover">
            @endif
        </div>

        <div class="mt-16 grid grid-cols-1 gap-12 lg:grid-cols-12">
            <aside data-reveal class="space-y-8 lg:col-span-4">
                @if ($project->techList())
                    <div>
                        <p class="label-mono mb-4">Technologies</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($project->techList() as $tech)
                                <span class="chip">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($project->category)
                    <div>
                        <p class="label-mono mb-2">Catégorie</p>
                        <p class="text-paper">{{ $project->category->name }}</p>
                    </div>
                @endif
                @if ($project->date)
                    <div>
                        <p class="label-mono mb-2">Date</p>
                        <p class="text-paper">{{ $project->date->translatedFormat('F Y') }}</p>
                    </div>
                @endif
            </aside>

            <div data-reveal class="lg:col-span-8">
                <p class="label-mono mb-4">À propos du projet</p>
                <x-rich-text :text="$project->description" lead>
                    <p class="text-lg text-paper-dim">Description à venir.</p>
                </x-rich-text>
            </div>
        </div>

        @if ($otherProjects->isNotEmpty())
            <section class="mt-28 border-t border-white/5 pt-16">
                <h2 data-reveal class="mb-12 font-serif text-4xl text-paper sm:text-5xl">Autres <em class="text-brass">projets</em></h2>
                <div data-reveal data-reveal-stagger class="grid grid-cols-1 gap-10 md:grid-cols-2">
                    @foreach ($otherProjects as $other)
                        <div>@include('partials.project-card', ['project' => $other])</div>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
@endsection
