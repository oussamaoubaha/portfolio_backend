<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Profile::updateOrCreate(
            ['email' => 'oussama.oubaha24@gmail.com'],
            [
                'name' => 'Oussama Oubaha',
                'title' => 'Full Stack Developer',
                'subtitle' => 'Développeur passionné par la création d\'expériences web modernes et performantes.',
                'description' => 'Spécialisé dans l\'écosystème React et Laravel, je conçois des applications scalables et intuitives. À la recherche d\'opportunités pour relever de nouveaux défis techniques.',
                'location' => 'Maroc',
                'hero_image' => '/OUSSAMA.jpg',
                'about_text' => "Je suis un développeur Full Stack passionné, avec une solide expérience dans la conception et le développement d'applications web.\n\nMon expertise couvre tout le cycle de développement, de l'architecture backend robuste avec Laravel à des interfaces frontend dynamiques et réactives avec React et TailwindCSS.\n\nToujours en veille technologique, j'aime explorer de nouveaux outils pour optimiser la performance et l'expérience utilisateur de mes projets.",
                'cv_url' => '/Oubaha_Oussama.pdf',
                'social_links' => [
                    'linkedin' => 'https://www.linkedin.com/in/oussama-oubaha-75951436a/',
                    'github' => 'https://github.com/oussama-oubaha',
                    'facebook' => 'https://www.facebook.com/oussama.ou.9699',
                    'instagram' => 'https://www.instagram.com/oussama.ou18/',
                    'whatsapp' => 'https://wa.me/+212628841979',
                ],
            ]
        );
    }
}
