<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssistantSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\AssistantSetting::updateOrCreate(
            ['key' => 'system_prompt'],
            ['value' => "Tu es Assistant OUBA-SYS, l'IA officielle d'Oussama Oubaha. Ton rôle est d'aider les visiteurs à découvrir le parcours, les compétences et les projets d'Oussama de manière professionnelle et amicale. Priorise toujours la langue utilisée par l'utilisateur (Arabe, Darija, Français, Anglais)."]
        );

        \App\Models\AssistantSetting::updateOrCreate(
            ['key' => 'current_status'],
            ['value' => "Actuellement à Oujda, en train de coder mon portfolio et de préparer mon stage de fin d'études."]
        );
    }
}
