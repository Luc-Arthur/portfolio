<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Génère une version statique du portfolio (HTML, CSS, JS, images, CV) pour GitHub Pages.
 *
 * Flux de travail : ajouter ses projets dans l'admin en local → php artisan portfolio:export → git push.
 */
class ExportStaticSite extends Command
{
    protected $signature = 'portfolio:export
        {--output=docs : Dossier de sortie, relatif à la racine du projet}
        {--url= : Adresse publique du site (par défaut : STATIC_SITE_URL)}';

    protected $description = 'Génère la version statique du portfolio pour GitHub Pages';

    /** Fichier témoin : on ne vide jamais un dossier qui n'a pas été créé par cette commande. */
    private const MARKER = '.portfolio-export';

    private string $out;

    private string $url;

    public function handle(Kernel $kernel): int
    {
        $this->url = rtrim($this->option('url') ?: config('portfolio.static_url'), '/');
        $this->out = base_path(trim($this->option('output'), '/\\'));

        if (!$this->checkPrerequisites()) {
            return self::FAILURE;
        }

        $this->prepareOutputDirectory();

        // Toutes les URL générées (liens, assets, sitemap) pointent vers l'adresse publique.
        config([
            'portfolio.static' => true,
            'session.driver' => 'array',
            'app.url' => $this->url,
        ]);
        URL::forceRootUrl($this->url);
        URL::forceScheme(parse_url($this->url, PHP_URL_SCHEME) ?: 'https');

        $images = $this->exportImages();

        $pages = ['/', '/projects', '/about', '/contact'];
        foreach (Project::orderBy('id')->pluck('slug') as $slug) {
            $pages[] = '/projects/' . $slug;
        }

        foreach ($pages as $path) {
            $html = $this->render($kernel, $path, 200);
            $this->write($path === '/' ? 'index.html' : ltrim($path, '/') . '/index.html', strtr($html, $images));
        }

        $this->write('404.html', strtr($this->render($kernel, '/page-introuvable-' . uniqid(), 404), $images));
        $this->write('sitemap.xml', $this->render($kernel, '/sitemap.xml', 200));
        $this->write('robots.txt', "User-agent: *\nAllow: /\n\nSitemap: {$this->url}/sitemap.xml\n");
        $this->write('.nojekyll', '');

        File::copyDirectory(public_path('build'), $this->out . '/build');
        File::copy(public_path('favicon.ico'), $this->out . '/favicon.ico');
        $cv = $this->exportCv();

        $this->newLine();
        $this->components->info('Site statique généré dans ' . $this->relative($this->out));
        $this->components->twoColumnDetail('Pages', (string) (count($pages) + 1));
        $this->components->twoColumnDetail('Images', (string) count($images));
        $this->components->twoColumnDetail('CV', $cv ? 'oui' : 'non');
        $this->components->twoColumnDetail('Formulaire de contact', config('portfolio.formspree_id') ? 'Formspree' : 'lien e-mail');
        $this->components->twoColumnDetail('Adresse', $this->url . '/');
        $this->newLine();
        $this->line('  Étape suivante : <comment>git add -A && git commit -m "Mise à jour du site" && git push</comment>');

        return self::SUCCESS;
    }

    private function checkPrerequisites(): bool
    {
        if (File::exists(public_path('hot'))) {
            $this->components->error('Vite tourne en mode développement (public/hot). Arrête « npm run dev » puis lance « npm run build ».');

            return false;
        }

        if (!File::exists(public_path('build/manifest.json'))) {
            $this->components->error('Les assets ne sont pas compilés. Lance d\'abord « npm run build ».');

            return false;
        }

        if ($this->out === base_path() || !str_starts_with($this->out, base_path() . DIRECTORY_SEPARATOR)) {
            $this->components->error('Le dossier de sortie doit être un sous-dossier du projet.');

            return false;
        }

        if (File::isDirectory($this->out) && !File::exists($this->out . '/' . self::MARKER) && count(File::allFiles($this->out, true)) > 0) {
            $this->components->error("Le dossier {$this->relative($this->out)} existe déjà et n'a pas été créé par cette commande. Choisis un autre dossier avec --output.");

            return false;
        }

        return true;
    }

    private function prepareOutputDirectory(): void
    {
        File::deleteDirectory($this->out);
        File::ensureDirectoryExists($this->out);
        File::put($this->out . '/' . self::MARKER, "Dossier généré par php artisan portfolio:export. Ne pas modifier à la main.\n");
    }

    private function render(Kernel $kernel, string $path, int $expectedStatus): string
    {
        $request = Request::create($path, 'GET');
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);

        if ($response->getStatusCode() !== $expectedStatus) {
            throw new \RuntimeException("{$path} a répondu {$response->getStatusCode()} au lieu de {$expectedStatus}.");
        }

        return $response->getContent();
    }

    /**
     * Copie les images utilisées par le site en les allégeant (JPEG, 1600 px de large maximum).
     *
     * @return array<string, string> URL d'origine => URL de l'image allégée, à remplacer dans le HTML
     */
    private function exportImages(): array
    {
        $paths = Project::whereNotNull('image')->pluck('image')
            ->push(Setting::first()?->about_image)
            ->filter(fn (?string $path) => $path && Storage::disk('public')->exists($path))
            ->unique();

        $replacements = [];

        foreach ($paths as $path) {
            $source = Storage::disk('public')->path($path);
            $target = preg_replace('/\.\w+$/', '', $path) . '.jpg';
            $destination = $this->out . '/storage/' . $target;

            File::ensureDirectoryExists(dirname($destination));

            if (!$this->optimizeImage($source, $destination)) {
                $target = $path;
                File::copy($source, $this->out . '/storage/' . $path);
            }

            $replacements[$this->url . '/storage/' . $path] = $this->url . '/storage/' . $target;
        }

        return $replacements;
    }

    private function optimizeImage(string $source, string $destination, int $maxWidth = 1600): bool
    {
        if (!function_exists('imagecreatefromstring') || !($image = @imagecreatefromstring(File::get($source)))) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = min(1, $maxWidth / $width);
        $newWidth = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        // Fond blanc pour les PNG transparents (le JPEG n'a pas de transparence)
        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imageinterlace($canvas, true);

        $saved = imagejpeg($canvas, $destination, 82);

        imagedestroy($image);
        imagedestroy($canvas);

        return $saved;
    }

    private function exportCv(): bool
    {
        $setting = Setting::first();

        if (!$setting?->hasCv()) {
            return false;
        }

        File::ensureDirectoryExists($this->out . '/cv');
        File::copy(Storage::disk('public')->path($setting->cv_path), $this->out . '/cv/' . $setting->cvFileName());

        return true;
    }

    private function write(string $relativePath, string $contents): void
    {
        $path = $this->out . '/' . $relativePath;
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $contents);
    }

    private function relative(string $path): string
    {
        return ltrim(str_replace(base_path(), '', $path), '/\\');
    }
}
