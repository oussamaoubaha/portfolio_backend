<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Experience::truncate();
        
        // Experiences
        \App\Models\Experience::create([
            'role' => 'Développeur Web Front-end',
            'company' => 'Maktoub-Tech',
            'location' => 'Fès, Maroc',
            'period' => '2025',
            'type' => 'Stage',
            'missions' => [
                "Développement d'une plateforme E-commerce complète et performante",
                "Utilisation de React.js et Tailwind CSS pour l'interface utilisateur",
                "Conception UX design centrée utilisateur pour une navigation intuitive",
                "Optimisation responsive pour une expérience parfaite sur tous les appareils",
            ],
        ]);

        // Education
        \App\Models\Experience::create([
            'role' => 'DUT en Conception et Développement des Logiciels',
            'company' => "EST d'Oujda",
            'location' => 'Oujda, Maroc',
            'period' => '2024 – 2026',
            'type' => 'Formation',
            'description' => 'Formation approfondie en génie logiciel couvrant la programmation, les bases de données, le développement web et les systèmes d\'information.',
            'missions' => [],
        ]);
    }
}
