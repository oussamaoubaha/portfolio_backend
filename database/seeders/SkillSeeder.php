<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Skill::truncate();
        $categories = [
            'Développement' => [
                'icon' => 'code',
                'items' => ["C/C++", "Java", "Python", "PHP (Laravel)"],
            ],
            'Web' => [
                'icon' => 'globe',
                'items' => ["React.js", "Tailwind CSS", "HTML/CSS"],
            ],
            'Data' => [
                'icon' => 'database',
                'items' => ["MySQL", "NoSQL", "Big Data", "Machine Learning"],
            ],
            'Systèmes' => [
                'icon' => 'server',
                'items' => ["Linux Ubuntu", "Réseaux", "Sécurité"],
            ],
        ];

        foreach ($categories as $categoryName => $data) {
            foreach ($data['items'] as $item) {
                \App\Models\Skill::create([
                    'name' => $item,
                    'category' => $categoryName,
                    'icon' => $data['icon'],
                    'level' => 80, // Default level
                ]);
            }
        }
    }
}
