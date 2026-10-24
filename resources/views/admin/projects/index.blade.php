@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Gestion des Projets</h1>
            <a href="{{ route('admin.projects.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajouter un Projet</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($project->image)
                                    <img src="{{ asset('storage/' . $project->image) }}"
                                        alt="{{ $project->title }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400">Pas d'image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $project->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $project->category->name ?? 'Aucune' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.projects.edit', $project) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucun projet trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
