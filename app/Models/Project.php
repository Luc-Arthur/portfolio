<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'image',
        'description',
        'date',
        'tech_stack',
        'github_url',
        'demo_url',
        'video_url',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'date' => 'date',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the category that this project belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Technologies sous forme de liste (« Laravel, Vue » → ['Laravel', 'Vue']).
     *
     * @return array<int, string>
     */
    public function techList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->tech_stack))));
    }

    /**
     * URL d'intégration YouTube ou Vimeo, ou null si le lien n'est pas reconnu.
     */
    public function videoEmbedUrl(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $this->video_url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?autoplay=1&rel=0';
        }

        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $this->video_url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1&dnt=1';
        }

        return null;
    }
}
