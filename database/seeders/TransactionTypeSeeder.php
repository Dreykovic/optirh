<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ajouter des types de transaction par défaut
        // TransactionType::create([
        //     'name' => 'Frais de maintenance',
        //     'code' => 'MAINTENANCE',
        //     'related_to' => 'DEBIT',
        //     'status' => 'Actif',
        // ]);

        TransactionType::create([
            'name' => 'Depot',
            'code' => 'DEPOSIT',
            'related_to' => 'CREDIT',
            'status' => 'Actif',
        ]);
        TransactionType::create([
            'name' => 'Retrait',
            'code' => 'WITHDRAWAL',
            'related_to' => 'DEBIT',
            'status' => 'Actif',
        ]);
        // TransactionType::create([
        //     'name' => 'Cotisation',
        //     'code' => 'CONTRIBUTION',
        //     'related_to' => 'CREDIT',
        //     'status' => 'Actif',
        // ]);
        // TransactionType::create([
        //     'name' => 'Intérêts',
        //     'code' => 'INTEREST-POST',
        //     'related_to' => 'CREDIT',
        //     'status' => 'Actif',
        // ]);
    }
}
