<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'email',
        'address',
        'twitter_url',
        'github_url',
        'linkedin_url',
        'cv_path',
        'hero_title',
        'hero_subtitle',
        'about_title',
        'about_subtitle',
        'about_description',
        'about_image',
    ];

    /**
     * Le CV est-il réellement présent sur le disque ? Évite d'afficher un lien cassé.
     */
    public function hasCv(): bool
    {
        return $this->cv_path && Storage::disk('public')->exists($this->cv_path);
    }

    protected function twitterUrl(): Attribute
    {
        return Attribute::get(fn (?string $value) => self::withScheme($value));
    }

    protected function githubUrl(): Attribute
    {
        return Attribute::get(fn (?string $value) => self::withScheme($value));
    }

    protected function linkedinUrl(): Attribute
    {
        return Attribute::get(fn (?string $value) => self::withScheme($value));
    }

    /**
     * « www.linkedin.com/in/x » → « https://www.linkedin.com/in/x », sinon le lien est relatif au site.
     */
    private static function withScheme(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        return preg_match('~^https?://~i', $url) ? $url : 'https://' . ltrim($url, '/');
    }
}
