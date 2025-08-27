<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Llama primero al seeder de roles para crearlos
        $this->call(RoleSeeder::class);

        // 2. Crea un usuario y le asigna el rol de Presidente
        $userPresidente = User::factory()->create([
            'name' => 'Presidente APR',
            'email' => 'presidente@santarita.cl',
        ]);
        $userPresidente->assignRole('Presidente');

        // 3. Crea un usuario y le asigna el rol de Tesorero
        $userTesorero = User::factory()->create([
            'name' => 'Tesorero APR',
            'email' => 'tesorero@santarita.cl',
        ]);
        $userTesorero->assignRole('Tesorero');

        // 4. Crea un usuario y le asigna el rol de Secretario
        $userSecretario = User::factory()->create([
            'name' => 'Secretario APR',
            'email' => 'secretario@santarita.cl',
        ]);
        $userSecretario->assignRole('Secretario');
    }
}