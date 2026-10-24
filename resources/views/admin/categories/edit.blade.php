@extends('layouts.admin')

@section('content')
    <div class="max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Modifier la catégorie</h1>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nom</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                    class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm text-slate-600">Annuler</a>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Mettre à
                    jour</button>
            </div>
        </form>
    </div>
@endsection


