@extends('layouts.front')

@section('title', 'À propos — ' . config('app.name'))

@section('content')
    <section class="mx-auto max-w-7xl px-5 pb-20 pt-40 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-32">
                    <figure data-hero class="relative mx-auto max-w-sm lg:mx-0">
                        <div aria-hidden="true" class="absolute -inset-3 -translate-x-4 translate-y-4 rounded-t-full rounded-b-3xl border border-brass/40"></div>
                        <div class="relative aspect-[3/4] overflow-hidden rounded-t-full rounded-b-3xl border border-white/10 bg-ink-800">
                            @if ($setting?->about_image)
                                <img src="{{ asset('storage/' . $setting->about_image) }}" alt="Portrait de {{ config('app.name') }}"
                                    class="h-full w-full scale-[1.2] object-cover object-top origin-top">
                            @endif
                        </div>
                    </figure>

                    <div data-hero class="mt-10 flex flex-wrap gap-3">
                        @if ($setting?->hasCv())
                            <a href="{{ route('cv') }}" class="btn-primary">
                                Télécharger mon CV <x-icon name="download" />
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="btn-ghost">Me contacter</a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7">
                <p data-hero class="label-mono mb-4">{{ $setting?->about_subtitle ?: 'À propos' }}</p>
                <h1 data-hero class="font-serif text-6xl leading-none text-paper sm:text-8xl">
                    {{ mb_convert_case($setting?->about_title ?: 'À propos', MB_CASE_TITLE) }}<span class="text-brass">.</span>
                </h1>

                <x-rich-text data-hero class="mt-12" :text="$setting?->about_description" lead>
                    <p class="text-lg text-paper-dim">Personnalise cette bio depuis l'espace d'administration.</p>
                </x-rich-text>
            </div>
        </div>
    </section>

    @if ($skills->isNotEmpty())
        <section class="border-t border-white/5 bg-ink-950/60">
            <div class="mx-auto max-w-7xl px-5 py-28 sm:px-8 lg:px-12">
                <div data-reveal class="mb-14">
                    <p class="label-mono mb-4">Boîte à outils</p>
                    <h2 class="font-serif text-5xl leading-none text-paper sm:text-6xl">Compé<em class="text-brass">tences</em></h2>
                </div>

                <div data-reveal data-reveal-stagger class="grid grid-cols-1 gap-px overflow-hidden rounded-3xl border border-white/5 bg-white/5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($skills as $group => $items)
                        <div class="bg-ink-900 p-8">
                            <h3 class="label-mono !text-brass">{{ $group }}</h3>
                            <ul class="mt-6 space-y-3">
                                @foreach ($items as $skill)
                                    <li class="flex items-center gap-3 text-paper">
                                        <span class="h-1 w-1 rounded-full bg-paper-faint"></span>{{ $skill->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
