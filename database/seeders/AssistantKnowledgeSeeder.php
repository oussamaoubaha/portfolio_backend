<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssistantKnowledge;

class AssistantKnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\AssistantKnowledge::truncate();

        \App\Models\AssistantKnowledge::create([
            'question' => 'Salutations',
            'keywords' => 'salam, bonjour, hi, hello, bjr, cc, سلام, salut, hey',
            'answer' => "Salam! Je suis Assistant OUBA-SYS, l'assistant intelligent d'Oussama. Enchanté de vous voir ici !",
        ]);

        \App\Models\AssistantKnowledge::create([
            'question' => 'Localisation',
            'keywords' => 'Où es-tu, fin nta, ville, emplacement, kyn f oujda, habitation, adresse',
            'answer' => "Oussama est actuellement basé à Oujda, mais il est souvent entre Oujda et El Hajeb pour ses projets et études.",
        ]);

        \App\Models\AssistantKnowledge::create([
            'question' => 'Statut Actuel',
            'keywords' => 'tu travailles, khedam, kate9ra, stage, occupation, recherche, disponible',
            'answer' => "Oussama est actuellement étudiant en Génie Informatique et il recherche activement un stage de fin d'études pour mettre ses compétences au service de projets innovants.",
        ]);

        $data = [
            [
                'question' => 'compétences',
                'keywords' => 'compétences, skills, technologies, maîtrise, langages, frameworks',
                'answer' => 'Oussama maîtrise les technologies suivantes : React.js, Laravel (PHP), Tailwind CSS, MySQL, Java, Python, C/C++, ainsi que Linux Ubuntu et Git.',
            ],
            [
                'question' => 'skills',
                'keywords' => 'compétences, skills, technologies, maîtrise, langages, frameworks',
                'answer' => 'Oussama maîtrise les technologies suivantes : React.js, Laravel (PHP), Tailwind CSS, MySQL, Java, Python, C/C++, ainsi que Linux Ubuntu et Git.',
            ],
            [
                'question' => 'contact',
                'answer' => 'Vous pouvez contacter Oussama par email à oussama.oubaha24@gmail.com ou via LinkedIn.',
            ],
            [
                'question' => 'email',
                'answer' => 'Son email professionnel est : oussama.oubaha24@gmail.com',
            ],
            [
                'question' => 'parcours',
                'answer' => 'Oussama est actuellement étudiant en Génie Informatique (2024–2026) à l\'EST d\'Oujda (DUT). Il a également travaillé comme Développeur Web Front-end chez Maktoub-Tech en 2025.',
            ],
            [
                'question' => 'formation',
                'answer' => 'Il prépare un DUT en Conception et Développement des Logiciels à l\'EST d\'Oujda (2024–2026).',
            ],
            [
                'question' => 'projet',
                'answer' => 'Oussama a travaillé sur plusieurs projets dont une plateforme E-commerce complète chez Maktoub-Tech et ce portfolio interactif.',
            ],
            [
                'question' => 'expérience',
                'answer' => 'Il a effectué un stage en tant que Développeur Web Front-end chez Maktoub-Tech en 2025, où il a développé une plateforme e-commerce.',
            ],
        ];

        foreach ($data as $item) {
            AssistantKnowledge::create($item);
        }
    }
}
