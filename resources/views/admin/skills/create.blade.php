@extends('layouts.admin')

@section('content')
    <div class="max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Nouvelle compétence</h1>

        <form action="{{ route('admin.skills.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nom</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Laravel, React, Docker..." required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="group_name" class="block text-sm font-medium text-slate-700">Groupe</label>
                <input type="text" name="group_name" id="group_name" value="{{ old('group_name') }}"
                    class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Backend, Frontend, DevOps, Outils...">
                @error('group_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-slate-700">Position</label>
                <input type="number" name="position" id="position" value="{{ old('position', 0) }}" min="0"
                    class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.skills.index') }}" class="px-4 py-2 text-sm text-slate-600">Annuler</a>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Enregistrer</button>
            </div>
        </form>
    </div>
@endsection
