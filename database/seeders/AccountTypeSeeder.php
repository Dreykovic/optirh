<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de plusieurs types de comptes
        AccountType::create([
            'name' => 'Savings Account',
            'account_number_prefix' => 'SA',
            'starting_account_number' => 0,

            'next_account_number' => 1001,
            'currency' => 'USD',
            'interest_rate' => 2.5,
            'interest_method' => 'Pro-Rata Basis',
            'interest_period' => 'Every 3 months',
            'minimum_balance_for_interest' => 100.0,
            'allow_withdraw' => true,
            'minimum_deposit_amount' => 50.0,
            'minimum_account_balance' => 50.0,
            'maintenance_fee' => 5.0,
            'maintenance_fee_posting_month' => 'January',
            'status' => 'Active',
        ]);

        AccountType::create([
            'name' => 'Tontine Account',
            'account_number_prefix' => 'TA',
            'starting_account_number' => 0,

            'next_account_number' => 2001,
            'currency' => 'XOF',
            'interest_rate' => 4.0,
            'interest_method' => 'Flat Rate',
            'interest_period' => 'Every 6 months',
            'minimum_balance_for_interest' => 10.0,
            'allow_withdraw' => false,
            'minimum_deposit_amount' => 10.0,
            'minimum_account_balance' => 10.0,
            'maintenance_fee' => 2.0,
            'maintenance_fee_posting_month' => 'March',
            'status' => 'Active',
        ]);

        AccountType::create([
            'name' => 'Main Account',
            'account_number_prefix' => 'MA',
            'starting_account_number' => 0,
            'next_account_number' => 3001,
            'currency' => 'EUR',
            'interest_rate' => 1.5,
            'interest_method' => 'Daily Outstanding Balance',
            'interest_period' => 'Monthly',
            'minimum_balance_for_interest' => 200.0,
            'allow_withdraw' => true,
            'minimum_deposit_amount' => 20.0,
            'minimum_account_balance' => 20.0,
            'maintenance_fee' => 10.0,
            'maintenance_fee_posting_month' => 'April',
            'status' => 'Active',
        ]);
    }
}
