<?php

namespace App\Console\Commands;

use App\Models\Socio;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LinkUsersToSocios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-users-socios'; // Nombre del comando

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vincula usuarios existentes con socios basados en su email'; // Descripción

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando vinculación de Usuarios con Socios...');

        $usersToProcess = User::whereNull('socio_id')->get(); // Solo procesa usuarios que aún no están vinculados
        $linkedCount = 0;
        $notFoundCount = 0;

        if ($usersToProcess->isEmpty()) {
            $this->info('No hay usuarios sin vincular. ¡Todo listo!');
            return Command::SUCCESS;
        }

        // Usamos una barra de progreso para mejor feedback
        $bar = $this->output->createProgressBar($usersToProcess->count());
        $bar->start();

        foreach ($usersToProcess as $user) {
            // Buscamos al socio por el email del usuario (case-insensitive por si acaso)
            $socio = Socio::whereRaw('LOWER(email) = ?', [strtolower($user->email)])->first();

            if ($socio) {
                // Si encontramos al socio, actualizamos el user
                // Usamos DB::update para evitar cargar/guardar el modelo User y ser más eficiente
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['socio_id' => $socio->id]);
                $linkedCount++;
            } else {
                // Si no encontramos socio para este email, lo reportamos
                $this->warn("\nAdvertencia: No se encontró socio para el usuario ID: {$user->id} (Email: {$user->email})");
                $notFoundCount++;
            }
            $bar->advance(); // Avanzamos la barra
        }

        $bar->finish(); // Terminamos la barra
        $this->info("\nProceso completado.");
        $this->info("Usuarios vinculados exitosamente: {$linkedCount}");
        if ($notFoundCount > 0) {
            $this->warn("Usuarios para los que no se encontró socio: {$notFoundCount}");
        }

        return Command::SUCCESS; // Indicamos que el comando terminó bien
    }
}