<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // <-- ESTA ES LA LÍNEA QUE FALTABA

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Presidente']);
        Role::create(['name' => 'Tesorero']);
        Role::create(['name' => 'Secretario']);
        Role::create(['name' => 'Socio']);
    }
}