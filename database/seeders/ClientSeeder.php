<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder; // Si vous souhaitez associer chaque client à un utilisateur existant

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de clients factices
        Client::create([
            'email' => 'john.doe@example.com',
            'name' => 'John Doe',
            'address1' => '123 Main St',
            'address2' => 'Apt 4B',
            'country' => 'Togo',
            'city' => 'Lomé',
            'state' => 'Maritime',
            'business_name' => 'Doe Enterprises',
            'client_no' => 'C000001',  // La génération automatique fonctionnera aussi
            'zip' => '00001',
            'job' => 'Entrepreneur',
            'phone' => '1234567890',
            'gender' => 'Masculin',
            'birthdate' => '1990-01-01',
            'status' => 'ACTIF',
            'profile_picture' => 'assets/images/profile_av.png',
        ]);

        Client::create([
            'email' => 'jane.doe@example.com',
            'name' => 'Jane Doe',

            'address1' => '456 Oak St',
            'address2' => 'Apt 3C',
            'country' => 'Togo',
            'city' => 'Sokodé',
            'state' => 'Tchaoudjo',
            'business_name' => 'Jane Solutions',
            'client_no' => 'C000002',
            'zip' => '00002',
            'job' => 'Consultant',
            'phone' => '0987654321',
            'gender' => 'Feminin',
            'birthdate' => '1985-05-12',
            'status' => 'ACTIF',
            'profile_picture' => 'assets/images/profile_av.png',
        ]);
    }
}
