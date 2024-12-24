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
            // compte
            'voir-un-compte',
            'écrire-un-compte',
            'créer-un-compte',
            'configurer-un-compte',

            // Employee
            'voir-un-employee',
            'écrire-un-employee',
            'créer-un-employee',
            'configurer-un-employee',

            // Attendance
            'voir-une-attendance',
            'écrire-une-attendance',
            'créer-une-attendance',
            'configurer-une-attendance',

            // All
            'voir-un-all',
            'écrire-un-all',
            'créer-un-all',
            'configurer-un-all',

            // Absence Request
            'voir-une-absence',
            'écrire-une-absence',
            'créer-une-absence',
            'configurer-une-absence',

            // Credentials
            'voir-un-credentials',
            'écrire-un-credentials',
            'créer-un-credentials',
            'configurer-un-credentials',

            // Role
            'voir-un-role',
            'écrire-un-role',
            'créer-un-role',
            'configurer-un-role',

            // Fériés
            'voir-un-férié',
            'écrire-un-férié',
            'créer-un-férié',
            'configurer-un-férié',
        ];
        $hr_permissions_list = [
            // compte
            'voir-un-compte',
            'écrire-un-compte',
            'créer-un-compte',
            'configurer-un-compte',

            // Employee
            'voir-un-employee',
            'écrire-un-employee',
            'créer-un-employee',
            'configurer-un-employee',

            // Attendance
            'voir-une-attendance',
            'écrire-une-attendance',
            'créer-une-attendance',
            'configurer-une-attendance',

            // Absence Request
            'voir-une-absence',
            'écrire-une-absence',
            'créer-une-absence',
            'configurer-une-absence',

            // Credentials
            'voir-un-credentials',
            'écrire-un-credentials',
            'créer-un-credentials',
            'configurer-un-credentials',

            // Fériés
            'voir-un-férié',
            'écrire-un-férié',
            'créer-un-férié',
            'configurer-un-férié',
        ];
        $dg_permissions_list = [
            // compte
            'voir-un-compte',
            'écrire-un-compte',
            'créer-un-compte',
            'configurer-un-compte',

            // Employee
            'voir-un-employee',
            'écrire-un-employee',
            'créer-un-employee',
            'configurer-un-employee',

            // Attendance
            'voir-une-attendance',
            'écrire-une-attendance',
            'créer-une-attendance',
            'configurer-une-attendance',

            // Absence Request
            'voir-une-absence',
            'écrire-une-absence',
            'créer-une-absence',
            'configurer-une-absence',

            // Credentials
            'voir-un-credentials',
            'écrire-un-credentials',
            'créer-un-credentials',
            'configurer-un-credentials',

            // Fériés
            'voir-un-férié',
            'écrire-un-férié',
            'créer-un-férié',
            'configurer-un-férié',
        ];
        $employee_permissions_list = [
            // compte
            'voir-un-compte',
            'écrire-un-compte',
            'créer-un-compte',
            'configurer-un-compte',

            // Attendance
            'voir-une-attendance',

            // Absence Request
            'voir-une-absence',

            'créer-une-absence',

            // Credentials
            'écrire-un-credentials',

            // Fériés
            'voir-un-férié',
        ];

        // Création des permission
        foreach ($permissions_list as $permission) {
            Permission::create(['name' => $permission]);
        }
        // Création des roles
        $admin = Role::create(['name' => 'ADMIN']);
        $hr = Role::create(['name' => 'GRH']);
        $dg = Role::create(['name' => 'DG']);
        $employee = Role::create(['name' => 'EMPLOYEE']);

        // Récupération des permissions
        $all_permissions = Permission::all();
        $admin_permissions = $all_permissions->whereIn('name', $permissions_list);
        $hr_permissions = $all_permissions->whereIn('name', $hr_permissions_list);
        $dg_permissions = $all_permissions->whereIn('name', $dg_permissions_list);
        $employee_permissions = $all_permissions->whereIn('name', $employee_permissions_list);

        // Synchronisation de chaque permission aux roles créés
        $admin->syncPermissions($admin_permissions);
        $hr->syncPermissions($hr_permissions);
        $dg->syncPermissions($dg_permissions);
        $employee->syncPermissions($employee_permissions);
    }
}
