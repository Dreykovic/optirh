<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AbsenceType;

class AbsenceTypeSeeder extends Seeder
{
    /**
     * Exécuter les commandes de remplissage de la base de données.
     *
     * @return void
     */
    public function run(): void
    {
        // Données fixes d'exemple
        $absenceTypes = [
            [
                'label' => 'Congé annuel',
                'description' => 'Absence pour congés payés annuels',
                'status' => 'ACTIVATED'
            ],
            [
                'label' => 'Congé maladie',
                'description' => 'Absence en cas de maladie avec justificatif médical',
                'status' => 'ACTIVATED'
            ],
            [
                'label' => 'Congé maternité',
                'description' => 'Congé accordé pour les salariées enceintes',
                'status' => 'ACTIVATED'
            ],
            [
                'label' => 'Congé paternité',
                'description' => 'Congé réservé au père de l’enfant après la naissance',
                'status' => 'ACTIVATED'
            ],
            [
                'label' => 'Absence injustifiée',
                'description' => 'Absence non justifiée par le salarié',
                'status' => 'DEACTIVATED'
            ],
            [
                'label' => 'Formation',
                'description' => 'Absence pour participer à une session de formation',
                'status' => 'ACTIVATED'
            ],
            [
                'label' => 'autre',
                'description' => 'Absence pour une raison spécifique',
                'status' => 'ACTIVATED'
            ]
        ];

        // Insérer les types d'absence fixes
        foreach ($absenceTypes as $type) {
            AbsenceType::create($type);
        }

        // Générer des types d'absence aléatoires (optionnel)
        AbsenceType::factory()->count(5)->create();
    }
}
