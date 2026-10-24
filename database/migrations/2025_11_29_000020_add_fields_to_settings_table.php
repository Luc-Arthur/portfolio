<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'phone')) {
                $table->string('phone')->nullable()->after('id');
            }
            if (!Schema::hasColumn('settings', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('settings', 'address')) {
                $table->string('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('address');
            }
            if (!Schema::hasColumn('settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('settings', 'twitter_url')) {
                $table->string('twitter_url')->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('settings', 'tiktok_url')) {
                $table->string('tiktok_url')->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('settings', 'about_title')) {
                $table->string('about_title')->nullable()->after('tiktok_url');
            }
            if (!Schema::hasColumn('settings', 'about_subtitle')) {
                $table->string('about_subtitle')->nullable()->after('about_title');
            }
            if (!Schema::hasColumn('settings', 'about_description')) {
                $table->text('about_description')->nullable()->after('about_subtitle');
            }
            if (!Schema::hasColumn('settings', 'about_image')) {
                $table->string('about_image')->nullable()->after('about_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'phone',
                'email',
                'address',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'tiktok_url',
                'about_title',
                'about_subtitle',
                'about_description',
                'about_image',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};


