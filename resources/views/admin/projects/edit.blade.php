@extends('layouts.admin')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Modifier le projet</h1>

        <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('title')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug <span class="text-gray-400 font-normal">(facultatif, généré depuis le titre)</span></label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $project->slug) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('slug')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                @if ($project->image)
                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}"
                        class="w-32 h-32 object-cover mb-2">
                @endif
                <input type="file" name="image" id="image"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept="image/*">
                @error('image')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" id="date" value="{{ old('date', $project->date) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('date')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tech_stack" class="block text-sm font-medium text-gray-700">Stack technique</label>
                <input type="text" name="tech_stack" id="tech_stack" value="{{ old('tech_stack', $project->tech_stack) }}"
                    placeholder="Laravel, Vue, PostgreSQL..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('tech_stack')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="github_url" class="block text-sm font-medium text-gray-700">Lien GitHub</label>
                    <input type="url" name="github_url" id="github_url"
                        value="{{ old('github_url', $project->github_url) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('github_url')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="demo_url" class="block text-sm font-medium text-gray-700">Lien démo</label>
                    <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url', $project->demo_url) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('demo_url')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                    <label for="video_url" class="block text-sm font-medium text-gray-700">Vidéo de démo (YouTube ou Vimeo)</label>
                    <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $project->video_url) }}"
                        placeholder="https://www.youtube.com/watch?v=..."
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('video_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_featured" value="1"
                        {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Mettre en avant sur la page d'accueil</span>
                </label>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour le projet</button>
        </form>
    </div>
@endsection
