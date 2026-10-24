@extends('layouts.front')

@php
    use Illuminate\Support\Str;

    // « Bonjour je suis Luc-Arthur LAWALE » → une accroche + le nom mis en valeur
    $heroTitle = $setting?->hero_title ?: 'Bonjour, je suis développeur';
    $greeting = null;
    $name = $heroTitle;

    if (preg_match('/^(.*?je suis)\s+(.+)$/iu', $heroTitle, $m)) {
        $greeting = rtrim(preg_replace('/^bonjour\s+/iu', 'Bonjour, ', $m[1]), ',');
        $name = mb_convert_case($m[2], MB_CASE_TITLE);
    }

    $role = $setting?->hero_subtitle ?: 'Développeur web';

    // Première phrase de la bio comme texte d'introduction
    $intro = Str::of($setting?->about_description ?? '')->explode("\n")->map('trim')->filter()->first()
        ?: "Je conçois des applications web modernes, performantes et agréables à utiliser.";
    $intro = Str::of($intro)->match('/^.+?[.!?](?=\s|$)/u')->toString() ?: $intro;

    $services = [
        ['title' => 'Front-end', 'text' => 'Interfaces réactives et ergonomiques avec HTML, CSS, JavaScript et les frameworks modernes (React, Vue, Angular).'],
        ['title' => 'Back-end', 'text' => 'Architectures solides et sécurisées avec PHP/Laravel, Node.js, Python ou Java.'],
        ['title' => 'Bases de données', 'text' => 'Conception et optimisation de schémas avec MySQL, PostgreSQL et MongoDB.'],
        ['title' => 'Déploiement', 'text' => 'Intégration continue, conteneurs Docker, hébergement cloud et versionnement Git.'],
    ];
@endphp

@section('content')
    {{-- HERO --}}
    <section class="relative isolate flex min-h-[min(100svh,980px)] items-center overflow-hidden">
        <div aria-hidden="true" class="absolute inset-0 -z-20 bg-[radial-gradient(ellipse_at_70%_40%,rgba(42,74,133,0.35),transparent_60%),radial-gradient(ellipse_at_10%_90%,rgba(227,164,58,0.10),transparent_50%)]"></div>
        <canvas id="hero-canvas" aria-hidden="true" class="absolute inset-0 -z-10 h-full w-full opacity-0 transition-opacity duration-[2000ms]"></canvas>

        <div class="mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-14 px-5 pb-20 pt-32 sm:px-8 lg:grid-cols-12 lg:gap-8 lg:px-12 lg:pt-28">
            <div class="lg:col-span-7">
                <p data-hero class="mb-8 inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/[0.03] px-4 py-2 font-mono text-xs text-paper-dim backdrop-blur">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    </span>
                    Disponible pour de nouveaux projets
                </p>

                @if ($greeting)
                    <p data-hero class="mb-3 font-serif text-2xl italic text-paper-dim sm:text-3xl">{{ $greeting }}</p>
                @endif

                <h1 data-hero class="font-serif text-[clamp(3.4rem,9vw,7.5rem)] leading-[0.92] tracking-tight text-paper">
                    {{ $name }}<span class="text-brass">.</span>
                </h1>

                <p data-hero class="mt-6 font-mono text-sm uppercase tracking-label text-brass">
                    {{ $role }}
                </p>

                <p data-hero class="mt-6 max-w-xl text-lg leading-relaxed text-paper-dim">
                    {{ Str::limit($intro, 240) }}
                </p>

                <div data-hero class="mt-10 flex flex-wrap items-center gap-4">
                    <a href="{{ route('projects') }}" class="btn-primary">
                        Voir mes projets <x-icon name="arrow-right" />
                    </a>
                    @if ($setting?->hasCv())
                        <a href="{{ route('cv') }}" class="btn-ghost">
                            Télécharger le CV <x-icon name="download" />
                        </a>
                    @else
                        <a href="{{ route('contact') }}" class="btn-ghost">Me contacter</a>
                    @endif

                    <div class="ml-1 flex items-center gap-4 text-paper-dim">
                        @if ($setting?->github_url)
                            <a href="{{ $setting->github_url }}" target="_blank" rel="noopener" aria-label="GitHub"
                                class="transition hover:text-brass-light"><x-icon name="github" class="h-5 w-5" /></a>
                        @endif
                        @if ($setting?->linkedin_url)
                            <a href="{{ $setting->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"
                                class="transition hover:text-brass-light"><x-icon name="linkedin" class="h-5 w-5" /></a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <figure data-hero class="relative mx-auto w-full max-w-sm lg:ml-auto lg:mr-0">
                    <div aria-hidden="true" class="absolute -inset-3 translate-x-4 translate-y-4 rounded-t-full rounded-b-3xl border border-brass/40"></div>
                    <div class="relative aspect-[4/5] overflow-hidden rounded-t-full rounded-b-3xl border border-white/10 bg-ink-800 shadow-[0_40px_80px_-30px_rgba(0,0,0,0.8)]">
                        @if ($setting?->about_image)
                            <img src="{{ asset('storage/' . $setting->about_image) }}" alt="Portrait de {{ $name }}"
                                fetchpriority="high" class="h-full w-full object-cover object-top scale-[1.75] origin-[50%_6%]">
                        @else
                            <div class="flex h-full items-center justify-center font-serif text-8xl text-white/10">
                                {{ mb_substr($name, 0, 1) }}
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-900/60 via-transparent to-transparent"></div>
                    </div>

                    <figcaption class="absolute -left-4 bottom-10 rounded-2xl border border-white/10 bg-ink-900/80 px-4 py-3 font-mono text-xs text-paper-dim backdrop-blur sm:-left-10">
                        <span class="text-brass">{</span> full&nbsp;stack <span class="text-brass">}</span>
                        <span class="mt-1 block text-[0.65rem] text-paper-faint">front · back · data · devops</span>
                    </figcaption>
                </figure>
            </div>
        </div>

        <a href="#selection" aria-label="Défiler vers les projets"
            class="absolute bottom-8 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-paper-faint md:flex">
            <span class="label-mono">Défiler</span>
            <span class="block h-8 w-px animate-scroll-hint bg-gradient-to-b from-brass to-transparent"></span>
        </a>
    </section>

    {{-- BANDEAU TECHNOLOGIES --}}
    <div class="relative overflow-hidden border-y border-white/5 bg-ink-950 py-6" aria-label="Technologies">
        <div class="flex w-max animate-marquee gap-12 whitespace-nowrap hover:[animation-play-state:paused]">
            @foreach ([1, 2] as $loopIndex)
                <ul class="flex shrink-0 items-center gap-12" @if ($loopIndex === 2) aria-hidden="true" @endif>
                    @foreach ($techs as $tech)
                        <li class="flex items-center gap-12 font-serif text-3xl italic text-paper/70">
                            {{ $tech }} <span class="text-base not-italic text-brass">✦</span>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>

    {{-- PROJETS --}}
    <section id="selection" class="mx-auto max-w-7xl scroll-mt-24 px-5 py-28 sm:px-8 lg:px-12">
        <div data-reveal class="mb-16 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="label-mono mb-4">Sélection de travaux</p>
                <h2 class="font-serif text-5xl leading-none text-paper sm:text-6xl">Projets <em class="text-brass">récents</em></h2>
            </div>
            <a href="{{ route('projects') }}" class="link-draw self-start pb-1 text-paper-dim hover:text-paper md:self-auto">
                Tous les projets →
            </a>
        </div>

        @if ($featuredProjects->isNotEmpty())
            <div data-reveal data-reveal-stagger class="grid grid-cols-1 gap-x-10 gap-y-16 md:grid-cols-2">
                @foreach ($featuredProjects as $project)
                    @php($wide = $loop->first && $featuredProjects->count() % 2 === 1)
                    <div class="{{ $wide ? 'md:col-span-2' : '' }}">
                        @include('partials.project-card', ['project' => $project, 'index' => $loop->index, 'wide' => $wide])
                    </div>
                @endforeach
            </div>
        @else
            <div data-reveal class="relative overflow-hidden rounded-3xl border border-dashed border-white/10 bg-ink-800/40 px-8 py-16 text-center">
                <p class="font-serif text-4xl text-paper">Projets en cours de publication</p>
                <p class="mx-auto mt-4 max-w-md text-paper-dim">
                    Mes réalisations arrivent très bientôt ici. En attendant, mon code est visible sur GitHub.
                </p>
                @if ($setting?->github_url)
                    <a href="{{ $setting->github_url }}" target="_blank" rel="noopener" class="btn-ghost mt-8">
                        <x-icon name="github" /> Voir mon GitHub
                    </a>
                @endif
            </div>
        @endif
    </section>

    {{-- SAVOIR-FAIRE --}}
    <section class="border-t border-white/5 bg-ink-950/60">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-16 px-5 py-28 sm:px-8 lg:grid-cols-12 lg:px-12">
            <div data-reveal class="lg:col-span-4">
                <p class="label-mono mb-4">Ce que je fais</p>
                <h2 class="font-serif text-5xl leading-none text-paper sm:text-6xl">
                    De l'idée<br>au <em class="text-brass">produit.</em>
                </h2>
                <p class="mt-6 max-w-sm text-paper-dim">
                    J'interviens sur toute la chaîne : de l'interface que voit l'utilisateur jusqu'au serveur qui la fait tourner.
                </p>
                <a href="{{ route('about') }}" class="btn-ghost mt-8">En savoir plus sur moi <x-icon name="arrow-right" /></a>
            </div>

            <ol data-reveal data-reveal-stagger class="grid grid-cols-1 gap-px overflow-hidden rounded-3xl border border-white/5 bg-white/5 sm:grid-cols-2 lg:col-span-8">
                @foreach ($services as $service)
                    <li class="group bg-ink-900 p-8 transition duration-500 hover:bg-ink-800 sm:p-10">
                        <span class="font-mono text-xs text-brass">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-6 font-serif text-3xl text-paper">{{ $service['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-paper-dim">{{ $service['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endsection
