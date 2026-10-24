@php
    $siteName = config('app.name');
    $pageTitle = trim($__env->yieldContent('title')) ?: $siteName . ' — ' . ($settings?->hero_subtitle ?: 'Développeur web');
    $pageDescription = trim($__env->yieldContent('description'))
        ?: \Illuminate\Support\Str::limit(strip_tags($settings?->about_description ?? 'Portfolio de développeur web full stack.'), 155);
    $ogImage = trim($__env->yieldContent('og_image'))
        ?: ($settings?->about_image ? asset('storage/' . $settings->about_image) : null);

    $gtmId = config('services.google.tag_manager_id');
    $gaId = config('services.google.analytics_id');
    $hasTracking = $gtmId || $gaId;

    $navItems = [
        ['route' => 'projects', 'label' => 'Projets', 'active' => request()->routeIs('projects*')],
        ['route' => 'about', 'label' => 'À propos', 'active' => request()->routeIs('about')],
        ['route' => 'contact', 'label' => 'Contact', 'active' => request()->routeIs('contact')],
    ];
@endphp
<!DOCTYPE html>
<html lang="fr" class="js-motion">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0e16">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Aperçu lors d'un partage (LinkedIn, WhatsApp, X…) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">

    {{-- Sans JavaScript, aucun contenu ne doit rester masqué --}}
    <noscript>
        <style>
            .js-motion [data-reveal],
            .js-motion [data-hero] {
                opacity: 1 !important;
            }
        </style>
    </noscript>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=instrument-serif:400,400i|hanken-grotesk:400,500,600,700|jetbrains-mono:400,500&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if ($hasTracking)
        <script>
            // Les outils de mesure ne se chargent qu'après accord du visiteur (RGPD).
            window.loadAnalytics = function() {
                if (window.__analyticsLoaded) return;
                window.__analyticsLoaded = true;
                window.dataLayer = window.dataLayer || [];

                @if ($gtmId)
                    window.dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
                    var s = document.createElement('script');
                    s.async = true;
                    s.src = 'https://www.googletagmanager.com/gtm.js?id=' + @json($gtmId);
                    document.head.appendChild(s);
                @else
                    function gtag() { dataLayer.push(arguments); }
                    window.gtag = gtag;
                    gtag('js', new Date());
                    gtag('config', @json($gaId), { anonymize_ip: true });
                    var s = document.createElement('script');
                    s.async = true;
                    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + @json($gaId);
                    document.head.appendChild(s);
                @endif
            };

            try {
                if (localStorage.getItem('cookie-consent') === 'accepted') window.loadAnalytics();
            } catch (e) {}
        </script>
    @endif
</head>

<body class="grain bg-ink-900 font-sans text-paper antialiased">
    <a href="#contenu"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[70] focus:rounded-full focus:bg-brass focus:px-4 focus:py-2 focus:text-ink-950">
        Aller au contenu
    </a>

    <div class="flex min-h-screen flex-col">
        <header x-data="{ open: false, scrolled: window.scrollY > 24 }" @scroll.window="scrolled = window.scrollY > 24"
            @keydown.escape.window="open = false"
            :class="scrolled || open ? 'bg-ink-900/80 backdrop-blur-xl !border-white/5' : ''"
            class="fixed inset-x-0 top-0 z-50 border-b border-transparent transition-colors duration-500">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8 lg:px-12">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <span class="h-2 w-2 rounded-full bg-brass transition-transform duration-500 group-hover:scale-150"></span>
                    <span class="font-serif text-xl tracking-tight text-paper sm:text-2xl">{{ $siteName }}</span>
                </a>

                <ul class="hidden items-center gap-10 text-sm md:flex">
                    @foreach ($navItems as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" @if ($item['active']) aria-current="page" @endif
                                class="link-draw pb-1 {{ $item['active'] ? 'text-paper' : 'text-paper-dim hover:text-paper' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                    @if ($settings?->hasCv())
                        <li>
                            <a href="{{ route('cv') }}"
                                class="btn-ghost !px-4 !py-2 text-xs">
                                CV <x-icon name="download" class="h-3.5 w-3.5" />
                            </a>
                        </li>
                    @endif
                </ul>

                <button @click="open = !open" class="relative h-10 w-10 md:hidden" :aria-expanded="open"
                    aria-controls="menu-mobile" aria-label="Ouvrir le menu">
                    <span :class="open ? 'translate-y-0 rotate-45' : '-translate-y-1.5'"
                        class="absolute left-2 right-2 top-1/2 h-px bg-paper transition duration-300"></span>
                    <span :class="open ? 'translate-y-0 -rotate-45' : 'translate-y-1.5'"
                        class="absolute left-2 right-2 top-1/2 h-px bg-paper transition duration-300"></span>
                </button>
            </nav>

            <div id="menu-mobile" x-show="open" x-cloak x-transition.opacity.duration.300ms
                class="h-[calc(100dvh-4.5rem)] px-5 pb-10 pt-6 md:hidden">
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="block py-2 font-serif text-5xl text-paper">Accueil</a></li>
                    @foreach ($navItems as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                                class="block py-2 font-serif text-5xl {{ $item['active'] ? 'text-brass' : 'text-paper' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                @if ($settings?->hasCv())
                    <a href="{{ route('cv') }}"
                        class="btn-primary mt-10">
                        Télécharger mon CV <x-icon name="download" />
                    </a>
                @endif
            </div>
        </header>

        <main id="contenu" class="flex-1">
            @yield('content')
        </main>

        <footer class="relative overflow-hidden border-t border-white/5 bg-ink-950">
            <div aria-hidden="true"
                class="pointer-events-none absolute -top-40 left-1/2 h-80 w-[48rem] -translate-x-1/2 rounded-full bg-brass/10 blur-3xl">
            </div>

            <div class="relative mx-auto max-w-7xl px-5 pb-10 pt-24 sm:px-8 lg:px-12">
                <div data-reveal class="flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="label-mono mb-6">Un projet, une opportunité ?</p>
                        <h2 class="font-serif text-5xl leading-[0.95] text-paper sm:text-7xl lg:text-8xl">
                            Travaillons<br><em class="text-brass">ensemble.</em>
                        </h2>
                    </div>

                    <div class="flex flex-col items-start gap-4 md:items-end">
                        @if ($settings?->email)
                            <a href="mailto:{{ $settings->email }}"
                                class="link-draw text-lg text-paper hover:text-brass-light sm:text-xl">
                                {{ $settings->email }}
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="btn-primary">
                            Me contacter <x-icon name="arrow-right" />
                        </a>
                    </div>
                </div>

                <div
                    class="mt-20 flex flex-col-reverse gap-6 border-t border-white/5 pt-8 text-sm text-paper-faint sm:flex-row sm:items-center sm:justify-between">
                    <p>&copy; {{ now()->year }} {{ $siteName }}. Conçu et développé avec Laravel.</p>

                    <div class="flex items-center gap-5">
                        @if ($settings?->github_url)
                            <a href="{{ $settings->github_url }}" target="_blank" rel="noopener" aria-label="GitHub"
                                class="transition hover:text-paper"><x-icon name="github" class="h-5 w-5" /></a>
                        @endif
                        @if ($settings?->linkedin_url)
                            <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"
                                class="transition hover:text-paper"><x-icon name="linkedin" class="h-5 w-5" /></a>
                        @endif
                        @if ($settings?->twitter_url)
                            <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener" aria-label="X"
                                class="transition hover:text-paper"><x-icon name="x" class="h-5 w-5" /></a>
                        @endif
                        <a href="#contenu" class="label-mono transition hover:text-paper">Haut de page ↑</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @if ($hasTracking)
        <div x-data="{
            show: false,
            init() { try { this.show = !localStorage.getItem('cookie-consent') } catch (e) {} },
            choose(value) {
                try { localStorage.setItem('cookie-consent', value) } catch (e) {}
                if (value === 'accepted') window.loadAnalytics();
                this.show = false;
            },
        }" x-show="show" x-cloak x-transition.opacity role="dialog" aria-label="Cookies"
            class="fixed inset-x-4 bottom-4 z-[65] mx-auto max-w-lg rounded-2xl border border-white/10 bg-ink-800/95 p-5 shadow-2xl backdrop-blur sm:inset-x-auto sm:right-6">
            <p class="text-sm text-paper-dim">
                J'utilise des cookies de mesure d'audience pour savoir quelles pages vous intéressent. Rien n'est
                chargé sans votre accord.
            </p>
            <div class="mt-4 flex gap-3">
                <button @click="choose('accepted')" class="btn-primary !px-5 !py-2">Accepter</button>
                <button @click="choose('refused')" class="btn-ghost !px-5 !py-2">Refuser</button>
            </div>
        </div>
    @endif
</body>

</html>
