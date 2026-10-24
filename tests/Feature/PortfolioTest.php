<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\Setting;

function makeProject(array $attributes = []): Project
{
    return Project::create(array_merge([
        'title' => 'Mon application',
        'slug' => 'mon-application',
        'category_id' => Category::create(['name' => 'Web'])->id,
        'description' => 'Une application de démonstration.',
        'tech_stack' => 'Laravel, Vue, MySQL',
    ], $attributes));
}

test('les pages publiques répondent', function (string $uri) {
    $this->get($uri)->assertOk();
})->with(['/', '/projects', '/about', '/contact', '/sitemap.xml']);

test('la page d\'accueil fonctionne sans paramètres ni projets', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Projets en cours de publication');
});

test('un projet a sa propre page accessible par son slug', function () {
    makeProject();

    $this->get('/projects/mon-application')
        ->assertOk()
        ->assertSee('Mon application')
        ->assertSee('Laravel');

    $this->get('/projects/inexistant')->assertNotFound();
});

test('la vidéo YouTube ou Vimeo est convertie en lecteur intégré', function (string $url, ?string $expected) {
    $project = new Project(['video_url' => $url]);

    expect($project->videoEmbedUrl())->toBe($expected);
})->with([
    ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?autoplay=1&rel=0'],
    ['https://youtu.be/dQw4w9WgXcQ', 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?autoplay=1&rel=0'],
    ['https://vimeo.com/76979871', 'https://player.vimeo.com/video/76979871?autoplay=1&dnt=1'],
    ['https://example.com/video', null],
]);

test('les liens sociaux sans https sont corrigés', function () {
    $setting = Setting::create(['linkedin_url' => 'www.linkedin.com/in/test']);

    expect($setting->linkedin_url)->toBe('https://www.linkedin.com/in/test');
});

test('le sitemap liste les projets', function () {
    makeProject();

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee(route('projects.show', 'mon-application'), false);
});

test('les scripts de mesure ne sont présents que si un identifiant est configuré', function () {
    $this->get('/')->assertDontSee('googletagmanager');

    config(['services.google.analytics_id' => 'G-TEST123']);

    $this->get('/')->assertSee('googletagmanager');
});

test('l\'admin peut modifier un projet et ajouter une vidéo', function () {
    $user = App\Models\User::factory()->create();
    $project = makeProject();

    $this->actingAs($user)
        ->get(route('admin.projects.edit', $project))
        ->assertOk()
        ->assertSee('name="video_url"', false);

    $this->actingAs($user)
        ->put(route('admin.projects.update', $project), [
            'title' => 'Mon application',
            'category_id' => $project->category_id,
            'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
        ])
        ->assertRedirect(route('admin.projects.index'));

    expect($project->fresh()->video_url)->toBe('https://youtu.be/dQw4w9WgXcQ');

    $this->actingAs($user)
        ->put(route('admin.projects.update', $project), [
            'title' => 'Mon application',
            'category_id' => $project->category_id,
            'video_url' => 'https://example.com/pas-une-video',
        ])
        ->assertSessionHasErrors('video_url');
});

test('le CV se télécharge avec un nom lisible', function () {
    Illuminate\Support\Facades\Storage::fake('public');
    Illuminate\Support\Facades\Storage::disk('public')->put('cv/mon-cv.pdf', '%PDF-1.4 test');
    Setting::create(['cv_path' => 'cv/mon-cv.pdf']);

    $this->get('/cv')
        ->assertOk()
        ->assertDownload('CV - ' . config('app.name') . '.pdf');

    $this->get('/')->assertSee(route('cv'));
});

test('si le fichier du CV a disparu, aucun lien cassé n\'est affiché', function () {
    Illuminate\Support\Facades\Storage::fake('public');
    Setting::create(['cv_path' => 'cv/introuvable.pdf']);

    $this->get('/cv')->assertNotFound();
    $this->get('/')->assertDontSee(route('cv'));
});
