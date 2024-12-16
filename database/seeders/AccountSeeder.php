<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques clients pour lier les comptes
        $clients = Client::all();
        $employees = User::where('profile', 'EMPLOYEE')->get();
        $assistants = User::where('profile', 'ASSISTANT')->get();
        $accountTypes = AccountType::all();

        // Création de quelques comptes fictifs
        foreach ($clients as $client) {
            // Sélectionner un employé et un assistant au hasard
            $employee = $employees->random();
            $assistant = $assistants->random();
            $accountType = $accountTypes->random();
            Account::create([
                'account_type_id' => $accountType->id, // Vous pouvez varier les types de comptes ici
                'state' => 'ACTIVE', // Exemple d'état, mais vous pouvez le modifier
                'current_balance' => rand(100, 10000), // Solde aléatoire entre 100 et 10000
                'loan_guarantee_amount' => rand(500, 5000), // Montant garanti aléatoire
                'fees' => rand(10, 200), // Frais aléatoires
                'creation_date' => now(),
                'employee_id' => $employee->id,
                'assistant_id' => $assistant->id, // L'assistant est facultatif
                'client_id' => $client->id, // Lier le compte au client
                'account_no' => 'ACC'.str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT), // Numéro de compte aléatoire
            ]);
        }
    }
}
