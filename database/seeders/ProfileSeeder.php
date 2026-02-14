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
        \App\Models\Profile::truncate();
        \App\Models\Profile::create([
            'name' => 'Oussama Oubaha',
            'title' => 'Étudiant en Génie Informatique',
            'subtitle' => 'À la recherche d\'un stage de fin d\'études',
            'description' => 'Passionné par le développement web et les technologies modernes. Je recherche un stage de fin d\'études de 2 mois pour contribuer à des projets innovants.',
            'email' => 'oussama.oubaha24@gmail.com',
            'location' => 'Maroc',
            'hero_image' => '/OUSSAMA.jpg',
            'about_text' => "Étudiant en 2ème année de génie informatique, je suis passionné par la conception et le développement de solutions logicielles innovantes.\n\nActuellement à la recherche d'un stage de fin d'études d'une durée de 2 mois, mon objectif est de contribuer à des projets innovants tout en consolidant mes compétences techniques dans un environnement professionnel stimulant.\n\nCurieux et motivé, j'aime relever des défis techniques et apprendre de nouvelles technologies pour créer des expériences utilisateur de qualité.",
            'cv_url' => '/Oubaha_Oussama.pdf',
            'social_links' => [
                'linkedin' => 'https://www.linkedin.com/in/oussama-oubaha-75951436a/',
                'github' => 'https://github.com/oussama-oubaha',
                'facebook' => 'https://www.facebook.com/oussama.ou.9699',
                'instagram' => 'https://www.instagram.com/oussama.ou18/',
                'whatsapp' => 'https://wa.me/+212628841979',
            ],
        ]);
    }
}
