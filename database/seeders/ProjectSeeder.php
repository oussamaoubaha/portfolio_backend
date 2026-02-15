<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'E-Commerce Pro',
                'description' => 'Une plateforme E-commerce complète avec panier, paiement Stripe et gestion des produits.',
                'image_url' => 'https://images.unsplash.com/photo-1557821552-17105176677c?w=800&q=80',
                'project_url' => 'https://github.com/oussama-oubaha/ecommerce-pro',
                'technologies' => ['React', 'Laravel', 'Tailwind', 'Stripe'],
                'order' => 1,
            ],
            [
                'title' => 'Dashboard Analytique',
                'description' => 'Interface d\'administration avec visualisation de données en temps réel via des graphiques interactifs.',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
                'project_url' => 'https://github.com/oussama-oubaha/analytics-dashboard',
                'technologies' => ['Vue.js', 'Firebase', 'Chart.js'],
                'order' => 2,
            ],
            [
                'title' => 'Application Fitness',
                'description' => 'Application mobile de suivi d\'entraînement et de nutrition personnalisée.',
                'image_url' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800&q=80',
                'project_url' => 'https://github.com/oussama-oubaha/fitness-app',
                'technologies' => ['React Native', 'Node.js', 'MongoDB'],
                'order' => 3,
            ],
            [
                'title' => 'Portfolio Interactif',
                'description' => 'Ce portfolio même, conçu pour présenter mes compétences et projets de manière élégante.',
                'image_url' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=800&q=80',
                'project_url' => 'https://github.com/oussama-oubaha/portfolio',
                'technologies' => ['React', 'Framer Motion', 'Tailwind'],
                'order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            \App\Models\Project::updateOrCreate(
                ['title' => $project['title']], // Unique key to check (Title)
                $project
            );
        }
    }
}
