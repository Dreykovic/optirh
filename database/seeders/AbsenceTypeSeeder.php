<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OptiHr\AbsenceType;

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
                'label' => 'annuel',
                'description' => 'Absence pour congés payés annuels',
                'status' => 'ACTIVATED'
            ],

            [
                'label' => 'maternité',
                'description' => 'Congé accordé pour les salariées enceintes',
                'status' => 'ACTIVATED'
            ],


            [
                'label' => 'exceptionnel',
                'description' => 'Absence pour une raison spécifique',
                'status' => 'ACTIVATED'
            ]
        ];

        // Insérer les types d'absence fixes
        foreach ($absenceTypes as $type) {
            AbsenceType::create($type);
        }

        // Générer des types d'absence aléatoires (optionnel)
        // AbsenceType::factory()->count(5)->create();
    }
}
