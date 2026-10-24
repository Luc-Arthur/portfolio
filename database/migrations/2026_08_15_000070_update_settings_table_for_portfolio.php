<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (['facebook_url', 'instagram_url', 'tiktok_url'] as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (!Schema::hasColumn('settings', 'github_url')) {
                $table->string('github_url')->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('settings', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('github_url');
            }
            if (!Schema::hasColumn('settings', 'cv_path')) {
                $table->string('cv_path')->nullable()->after('linkedin_url');
            }
            if (!Schema::hasColumn('settings', 'hero_title')) {
                $table->string('hero_title')->nullable()->after('cv_path');
            }
            if (!Schema::hasColumn('settings', 'hero_subtitle')) {
                $table->string('hero_subtitle')->nullable()->after('hero_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (['github_url', 'linkedin_url', 'cv_path', 'hero_title', 'hero_subtitle'] as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (!Schema::hasColumn('settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'tiktok_url')) {
                $table->string('tiktok_url')->nullable();
            }
        });
    }
};
