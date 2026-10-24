@extends('layouts.front')

@section('title', 'Contact — ' . config('app.name'))
@section('description', 'Contacter ' . config('app.name') . ' pour un projet web, une mission ou une opportunité.')

@section('content')
    <section class="mx-auto max-w-7xl px-5 pb-28 pt-40 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p data-hero class="label-mono mb-4">Contact</p>
                <h1 data-hero class="font-serif text-6xl leading-[0.95] text-paper sm:text-7xl">
                    Parlons de votre <em class="text-brass">projet.</em>
                </h1>
                <p data-hero class="mt-6 max-w-md text-lg text-paper-dim">
                    Une question, une mission, une opportunité ? Écrivez-moi, je réponds généralement sous 48&nbsp;heures.
                </p>

                <ul data-hero class="mt-12 space-y-5">
                    @if ($settings?->email)
                        <li>
                            <a href="mailto:{{ $settings->email }}" class="group flex items-center gap-4 text-paper">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 text-brass transition group-hover:border-brass"><x-icon name="mail" class="h-5 w-5" /></span>
                                <span class="link-draw">{{ $settings->email }}</span>
                            </a>
                        </li>
                    @endif
                    @if ($settings?->phone)
                        <li>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $settings->phone) }}" class="group flex items-center gap-4 text-paper">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 text-brass transition group-hover:border-brass"><x-icon name="phone" class="h-5 w-5" /></span>
                                <span class="link-draw">{{ $settings->phone }}</span>
                            </a>
                        </li>
                    @endif
                    @if ($settings?->address)
                        <li class="flex items-center gap-4 text-paper">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 text-brass"><x-icon name="pin" class="h-5 w-5" /></span>
                            {{ $settings->address }}
                        </li>
                    @endif
                </ul>
            </div>

            <div data-hero class="lg:col-span-7">
                <div class="rounded-3xl border border-white/10 bg-ink-800/40 p-6 backdrop-blur sm:p-10">
                    @if (session('success'))
                        <div role="status" class="mb-8 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-5 py-4 text-sm text-emerald-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                        @csrf

                        {{-- Honeypot anti-spam --}}
                        <input type="text" name="website" tabindex="-1" autocomplete="off" class="absolute -left-[9999px]" aria-hidden="true">

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="label-mono">Nom</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="name" class="field" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="label-mono">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email" class="field" required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="label-mono">Sujet <span class="normal-case tracking-normal">(facultatif)</span></label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="field">
                            @error('subject')
                                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="label-mono">Message</label>
                            <textarea name="message" id="message" rows="6" class="field resize-y" required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            Envoyer le message <x-icon name="arrow-right" />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
