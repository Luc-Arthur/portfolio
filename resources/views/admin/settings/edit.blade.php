@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl">
        <h1 class="text-2xl font-bold mb-6">Paramètres du site</h1>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold mb-2">Contact</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700">Téléphone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $setting->phone) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="text" name="email" id="email" value="{{ old('email', $setting->email) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-slate-700">Adresse</label>
                        <input type="text" name="address" id="address"
                            value="{{ old('address', $setting->address) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold mb-2">Réseaux &amp; liens</h2>
                <div class="space-y-4">
                    <div>
                        <label for="github_url" class="block text-sm font-medium text-slate-700">GitHub</label>
                        <input type="text" name="github_url" id="github_url"
                            value="{{ old('github_url', $setting->github_url) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('github_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="linkedin_url" class="block text-sm font-medium text-slate-700">LinkedIn</label>
                        <input type="text" name="linkedin_url" id="linkedin_url"
                            value="{{ old('linkedin_url', $setting->linkedin_url) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('linkedin_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="twitter_url" class="block text-sm font-medium text-slate-700">X / Twitter</label>
                        <input type="text" name="twitter_url" id="twitter_url"
                            value="{{ old('twitter_url', $setting->twitter_url) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('twitter_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="cv_file" class="block text-sm font-medium text-slate-700">CV (PDF)</label>
                        @if ($setting->cv_path)
                            <p class="text-sm text-slate-500 mb-1">
                                <a href="{{ asset('storage/' . $setting->cv_path) }}" target="_blank"
                                    class="text-blue-600 underline">CV actuel</a>
                            </p>
                        @endif
                        <input type="file" name="cv_file" id="cv_file" accept="application/pdf"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('cv_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold mb-2">Page d'accueil (héros)</h2>
                <div class="space-y-4">
                    <div>
                        <label for="hero_title" class="block text-sm font-medium text-slate-700">Titre</label>
                        <input type="text" name="hero_title" id="hero_title"
                            value="{{ old('hero_title', $setting->hero_title) }}" placeholder="Bonjour, je suis ..."
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('hero_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="hero_subtitle" class="block text-sm font-medium text-slate-700">Sous-titre</label>
                        <input type="text" name="hero_subtitle" id="hero_subtitle"
                            value="{{ old('hero_subtitle', $setting->hero_subtitle) }}"
                            placeholder="Développeur web full-stack"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('hero_subtitle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold mb-2">Page À propos</h2>
                <div class="space-y-4">
                    <div>
                        <label for="about_title" class="block text-sm font-medium text-slate-700">Titre</label>
                        <input type="text" name="about_title" id="about_title"
                            value="{{ old('about_title', $setting->about_title) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('about_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="about_subtitle" class="block text-sm font-medium text-slate-700">Sous-titre / Nom</label>
                        <input type="text" name="about_subtitle" id="about_subtitle"
                            value="{{ old('about_subtitle', $setting->about_subtitle) }}"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('about_subtitle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="about_description" class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea name="about_description" id="about_description" rows="4"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('about_description', $setting->about_description) }}</textarea>
                        @error('about_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="about_image" class="block text-sm font-medium text-slate-700">Image principale</label>
                        @if ($setting->about_image)
                            <img src="{{ asset('storage/' . $setting->about_image) }}" alt="About"
                                class="h-24 w-40 object-cover rounded mb-2">
                        @endif
                        <input type="file" name="about_image" id="about_image" accept="image/*"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('about_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="flex justify-end gap-2">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Enregistrer les
                    modifications</button>
            </div>
        </form>
    </div>
@endsection


