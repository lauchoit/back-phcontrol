<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super_admin',        // Full access to everything

            'system_admin',       // Manages users, catalogs, maps, crews, activities
            'operations_manager', // Operational control (maps, progress, reports)

            'field_supervisor',   // Creates progress entries and uploads evidence
            'data_entry_clerk',   // Manual data input (PDF vaciamiento)

            'auditor',            // Read-only + validation (optional workflow)

            'client_admin',       // Client-level access + manage client users
            'client_viewer',      // Read-only client access (download reports)
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role, 'api');
        }
    }
}
