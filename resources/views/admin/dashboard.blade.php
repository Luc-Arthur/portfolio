@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Dashboard Administration</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Projets</h3>
                <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Project::count() }}</p>
                <a href="{{ route('admin.projects.index') }}" class="text-blue-500 hover:text-blue-700">Gérer</a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Catégories</h3>
                <p class="text-3xl font-bold text-green-600">{{ \App\Models\Category::count() }}</p>
                <a href="{{ route('admin.categories.index') }}" class="text-green-500 hover:text-green-700">Gérer</a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Compétences</h3>
                <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Skill::count() }}</p>
                <a href="{{ route('admin.skills.index') }}" class="text-purple-500 hover:text-purple-700">Gérer</a>
            </div>
        </div>
    </div>
@endsection
