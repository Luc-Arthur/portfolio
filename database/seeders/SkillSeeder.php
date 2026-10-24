<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

/**
 * Compétences de départ, reprises de la bio. À ajuster ensuite depuis /admin/skills.
 *
 * php artisan db:seed --class=SkillSeeder
 */
class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Front-end' => ['HTML / CSS', 'JavaScript', 'React', 'Vue.js', 'Angular', 'Tailwind CSS'],
            'Back-end' => ['PHP', 'Laravel', 'Node.js', 'Python', 'Java'],
            'Bases de données' => ['MySQL', 'PostgreSQL', 'MongoDB'],
            'Outils & DevOps' => ['Git', 'Docker', 'CI/CD', 'Hébergement cloud'],
        ];

        foreach ($groups as $group => $skills) {
            foreach ($skills as $position => $name) {
                Skill::firstOrCreate(
                    ['name' => $name, 'group_name' => $group],
                    ['position' => $position],
                );
            }
        }
    }
}
