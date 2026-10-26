<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HomeController extends Controller
{
    /**
     * Page d'accueil publique.
     */
    public function index(): View
    {
        $setting = Setting::first();

        $featuredProjects = Project::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        if ($featuredProjects->isEmpty()) {
            $featuredProjects = Project::with('category')->latest()->take(6)->get();
        }

        // Bandeau de technologies : compétences saisies dans l'admin, sinon celles de la bio.
        $techs = Skill::orderBy('position')->pluck('name');

        if ($techs->isEmpty()) {
            $techs = collect(['Laravel', 'PHP', 'JavaScript', 'React', 'Vue.js', 'Node.js', 'Python', 'MySQL', 'PostgreSQL', 'MongoDB', 'Docker', 'Git', 'Tailwind CSS']);
        }

        return view('home', compact('setting', 'featuredProjects', 'techs'));
    }

    /**
     * Liste des projets. Le filtre par catégorie se fait dans le navigateur,
     * pour fonctionner aussi dans la version statique (GitHub Pages).
     */
    public function projects(Request $request): View
    {
        $projects = Project::with('category')->latest()->get();

        // Seules les catégories qui contiennent des projets servent de filtre
        $categories = Category::whereHas('projects')->orderBy('name')->get();

        $selectedCategory = $request->query('category');

        return view('projects', compact('projects', 'categories', 'selectedCategory'));
    }

    /**
     * Page de détail d'un projet.
     */
    public function show(Project $project): View
    {
        $project->load('category');

        $otherProjects = Project::with('category')
            ->whereKeyNot($project->getKey())
            ->latest()
            ->take(2)
            ->get();

        return view('project', compact('project', 'otherProjects'));
    }

    /**
     * Téléchargement du CV, avec un nom de fichier lisible.
     */
    public function cv(): StreamedResponse
    {
        $setting = Setting::first();

        abort_unless($setting?->hasCv(), 404);

        return Storage::disk('public')->download($setting->cv_path, 'CV - ' . config('app.name') . '.pdf');
    }

    /**
     * Sitemap XML pour les moteurs de recherche.
     */
    public function sitemap(): Response
    {
        $projects = Project::latest('updated_at')->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('projects'))
            ->header('Content-Type', 'application/xml');
    }
}
