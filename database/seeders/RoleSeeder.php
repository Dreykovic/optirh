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
            'voir-un-attendance',
            'écrire-un-attendance',
            'créer-un-attendance',
            'configurer-un-attendance',

            // All
            'voir-un-all',
            'écrire-un-all',
            'créer-un-all',
            'configurer-un-all',

            // Absence Request
            'voir-un-absence',
            'écrire-un-absence',
            'créer-un-absence',
            'configurer-un-absence',

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
            'voir-un-attendance',
            'écrire-un-attendance',
            'créer-un-attendance',
            'configurer-un-attendance',

            // Absence Request
            'voir-un-absence',
            'écrire-un-absence',
            'créer-un-absence',
            'configurer-un-absence',

            // Credentials
            'voir-un-credentials',
            'écrire-un-credentials',
            'créer-un-credentials',
            'configurer-un-credentials',
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
            'voir-un-attendance',
            'écrire-un-attendance',
            'créer-un-attendance',
            'configurer-un-attendance',

            // Absence Request
            'voir-un-absence',
            'écrire-un-absence',
            'créer-un-absence',
            'configurer-un-absence',

            // Credentials
            'voir-un-credentials',
            'écrire-un-credentials',
            'créer-un-credentials',
            'configurer-un-credentials',
        ];
        $employee_permissions_list = [
            // compte
            'voir-un-compte',
            'écrire-un-compte',
            'créer-un-compte',
            'configurer-un-compte',

            // Attendance
            'voir-un-attendance',

            // Absence Request
            'voir-un-absence',

            'créer-un-absence',

            // Credentials
            'écrire-un-credentials',
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
