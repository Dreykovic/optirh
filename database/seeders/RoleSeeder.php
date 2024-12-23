<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ensemble des permissions
        $permissions_list = [
            // Account
            'read-account',
            'write-account',
            'create-account',
            // deposit

            'read-deposit',
            'write-deposit',
            'create-deposit',

            // withdrawal

            'read-withdrawal',
            'write-withdrawal',
            'create-withdrawal',

            // contribution

            'read-contribution',
            'write-contribution',
            'create-contribution',

            // refund

            'read-refund',
            'write-refund',
            'create-refund',
            // Inflow

            'read-inflow',
            'write-inflow',
            'create-inflow',

            // Outflow

            'read-outflow',
            'write-outflow',
            'create-outflow',

            // Client

            'read-client',
            'write-client',
            'create-client',
            // Employee

            'read-employee',
            'write-employee',
            'create-employee',
            // All

            'read-all',
            'write-all',
            'create-all',
        ];

        $boss_permissions_list = [
            // Account
            'read-account',
            'write-account',
            'create-account',
            // deposit

            'read-deposit',
            'write-deposit',
            'create-deposit',

            // withdrawal

            'read-withdrawal',
            'write-withdrawal',
            'create-withdrawal',

            // contribution

            'read-contribution',
            'write-contribution',
            'create-contribution',

            // refund

            'read-refund',
            'write-refund',
            'create-refund',
            // Inflow

            'read-inflow',
            'write-inflow',
            'create-inflow',

            // Outflow

            'read-outflow',
            'write-outflow',
            'create-outflow',

            // Client

            'read-client',
            'write-client',
            'create-client',
            // Employee

            'read-employee',
            'write-employee',
            'create-employee',
            // All

            'read-all',
            'write-all',
            'create-all',
        ];
        $admin_permissions_list = [
            // Account
            'read-account',
            'write-account',
            'create-account',
            // deposit

            'read-deposit',
            'write-deposit',
            'create-deposit',

            // withdrawal

            'read-withdrawal',
            'write-withdrawal',
            'create-withdrawal',

            // contribution

            'read-contribution',
            'write-contribution',
            'create-contribution',

            // refund

            'read-refund',
            'write-refund',
            'create-refund',
            // Inflow

            'read-inflow',
            'write-inflow',
            'create-inflow',

            // Outflow

            'read-outflow',
            'write-outflow',
            'create-outflow',

            // Client

            'read-client',
            'write-client',
            'create-client',
            // Employee

            'read-employee',
            'write-employee',
            'create-employee',
            // All

            'read-all',
            'write-all',
            'create-all',
        ];
        $cashier_permissions_list = [
            // Account
            'read-account',
            'write-account',
            'create-account',
            // deposit

            'read-deposit',
            'write-deposit',
            'create-deposit',

            // withdrawal

            'read-withdrawal',
            'write-withdrawal',
            'create-withdrawal',

            // contribution

            'read-contribution',
            'write-contribution',
            'create-contribution',

            // refund

            'read-refund',
            'write-refund',
            'create-refund',
            // Inflow

            'read-inflow',
            'write-inflow',
            'create-inflow',

            // Outflow

            'read-outflow',
            'write-outflow',
            'create-outflow',

            // Client

            'read-client',
            'write-client',
            'create-client',
            // Employee

            'read-employee',
            'write-employee',
            'create-employee',
            // All

            'read-all',
            'write-all',
            'create-all',
        ];
        $accountant_permissions_list = [
            // Account
            'read-account',
            'write-account',
            'create-account',
            // deposit

            'read-deposit',
            'write-deposit',
            'create-deposit',

            // withdrawal

            'read-withdrawal',
            'write-withdrawal',
            'create-withdrawal',

            // contribution

            'read-contribution',
            'write-contribution',
            'create-contribution',

            // refund

            'read-refund',
            'write-refund',
            'create-refund',
            // Inflow

            'read-inflow',
            'write-inflow',
            'create-inflow',

            // Outflow

            'read-outflow',
            'write-outflow',
            'create-outflow',

            // Client

            'read-client',
            'write-client',
            'create-client',
            // Employee

            'read-employee',
            'write-employee',
            'create-employee',
            // All

            'read-all',
            'write-all',
            'create-all',
        ];

        // Création des permission
        foreach ($permissions_list as $permission) {
            Permission::create(['name' => $permission]);
        }
        // Création des roles
        $admin = Role::create(['name' => 'admin']);
        $boss = Role::create(['name' => 'boss']);
        $accountant = Role::create(['name' => 'accountant']);
        $cashier = Role::create(['name' => 'cashier']);
        Role::create(['name' => 'main']);
        Role::create(['name' => 'client']);
        Role::create(['name' => 'assistant']);

        // Récupération des permissions
        $all_permissions = Permission::all();
        $admin_permissions = $all_permissions->whereIn('name', $admin_permissions_list);
        $boss_permissions = $all_permissions->whereIn('name', $boss_permissions_list);
        $cashier_permissions = $all_permissions->whereIn('name', $cashier_permissions_list);
        $accountant_permissions = $all_permissions->whereIn('name', $accountant_permissions_list);

        // Synchronisation de chaque permission aux roles créés
        $admin->syncPermissions($admin_permissions);
        $boss->syncPermissions($boss_permissions);
        $cashier->syncPermissions($cashier_permissions);
        $accountant->syncPermissions($accountant_permissions);
    }
}
