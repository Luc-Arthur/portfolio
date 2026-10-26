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

test('les technologies sont découpées sur les virgules et « et »', function () {
    $project = new Project(['tech_stack' => 'HTML, CSS et JavaScript natif']);

    expect($project->techList())->toBe(['HTML', 'CSS', 'JavaScript natif']);
});

test('le texte saisi dans l\'admin est mis en forme', function () {
    $blocks = App\Support\RichText::parse(<<<'TXT'
        Fluxo est une landing page pour un SaaS financier.

        Un parcours pensé pour convertir
        La page suit une progression claire.

        💻 Ce que je fais
        Front-end : interfaces réactives.

        Mon approche repose sur :
        La qualité du code.
        L'expérience utilisateur.
        TXT);

    expect(array_column($blocks, 'type'))
        ->toBe(['text', 'heading', 'text', 'heading', 'term', 'text', 'item', 'item'])
        ->and($blocks[1]['text'])->toBe('Un parcours pensé pour convertir')
        ->and($blocks[3]['text'])->toBe('Ce que je fais')
        ->and($blocks[4]['term'])->toBe('Front-end');
});

test('l\'export statique génère un site complet pour GitHub Pages', function () {
    Illuminate\Support\Facades\Storage::fake('public');
    $disk = Illuminate\Support\Facades\Storage::disk('public');

    $image = imagecreatetruecolor(2400, 1500);
    ob_start();
    imagepng($image);
    $disk->put('projects/capture.png', ob_get_clean());
    $disk->put('cv/cv.pdf', '%PDF-1.4 test');

    Setting::create(['cv_path' => 'cv/cv.pdf', 'email' => 'moi@example.com']);
    makeProject(['image' => 'projects/capture.png']);

    $output = 'storage/framework/testing/export';
    $this->artisan('portfolio:export', ['--output' => $output, '--url' => 'https://exemple.github.io/portfolio'])
        ->assertSuccessful();

    $dir = base_path($output);

    foreach (['index.html', 'projects/index.html', 'projects/mon-application/index.html', 'about/index.html',
        'contact/index.html', '404.html', 'sitemap.xml', 'robots.txt', '.nojekyll', 'cv/cv-' . Str::slug(config('app.name')) . '.pdf',
        'storage/projects/capture.jpg', 'build/manifest.json'] as $file) {
        expect(file_exists("$dir/$file"))->toBeTrue("$file manquant");
    }

    $home = file_get_contents("$dir/index.html");
    expect($home)
        ->toContain('https://exemple.github.io/portfolio/projects/mon-application')
        ->toContain('https://exemple.github.io/portfolio/storage/projects/capture.jpg')
        ->not->toContain('capture.png')
        ->not->toContain('localhost');

    // L'image est réduite à 1600 px de large
    expect(getimagesize("$dir/storage/projects/capture.jpg")[0])->toBe(1600);

    // Sans Formspree, le formulaire de contact devient un lien e-mail
    expect(file_get_contents("$dir/contact/index.html"))
        ->toContain('mailto:moi@example.com')
        ->not->toContain('_token');

    Illuminate\Support\Facades\File::deleteDirectory($dir);
});

test('l\'export refuse d\'écraser un dossier qu\'il n\'a pas créé', function () {
    $dir = base_path('storage/framework/testing/dossier-perso');
    Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
    file_put_contents("$dir/important.txt", 'ne pas supprimer');

    $this->artisan('portfolio:export', ['--output' => 'storage/framework/testing/dossier-perso'])
        ->assertFailed();

    expect(file_exists("$dir/important.txt"))->toBeTrue();

    Illuminate\Support\Facades\File::deleteDirectory($dir);
});
