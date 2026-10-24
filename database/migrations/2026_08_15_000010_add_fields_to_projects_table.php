<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'tech_stack')) {
                $table->string('tech_stack')->nullable()->after('description');
            }
            if (!Schema::hasColumn('projects', 'github_url')) {
                $table->string('github_url')->nullable()->after('tech_stack');
            }
            if (!Schema::hasColumn('projects', 'demo_url')) {
                $table->string('demo_url')->nullable()->after('github_url');
            }
            if (!Schema::hasColumn('projects', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('demo_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            foreach (['tech_stack', 'github_url', 'demo_url', 'is_featured'] as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
