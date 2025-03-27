<?php

namespace Database\Seeders;

use App\Models\OptiHr\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Données fixes d'exemple
        $documentTypes = [
            [
                'label' => 'Attestation De Stage',
                'description' => 'Attestation Remise en fin de stage',
                'status' => 'ACTIVATED',
                "type" => "NORMAL"
            ],

            [
                'label' => 'Attestation de travail',
                'description' => '',
                'status' => 'ACTIVATED',
                "type" => "EXCEPTIONAL"
            ],
        ];

        // Insérer les types d'absence fixes
        foreach ($documentTypes as $type) {
            DocumentType::create($type);
        }
    }
}
