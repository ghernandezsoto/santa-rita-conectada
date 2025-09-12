<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;

class DiagnoseQueue extends Command
{
    protected $signature = 'app:diagnose-queue';
    protected $description = 'Procesa un solo trabajo de la cola con máximo detalle para diagnóstico.';

    public function handle(Worker $worker)
    {
        $this->info('Iniciando diagnóstico de la cola...');

        $jobCount = DB::table('jobs')->count();
        if ($jobCount === 0) {
            $this->warn('La tabla de trabajos (jobs) está vacía. Por favor, envía un comunicado desde la web para añadir un trabajo a la cola y vuelve a ejecutar este comando.');
            return 1;
        }

        $this->info("Se encontraron {$jobCount} trabajos en la cola. Intentando procesar uno...");

        try {
            // Forzamos al trabajador a procesar un solo trabajo y no detenerse ante errores
            $worker->runNextJob('database', 'default', new WorkerOptions());
            $this->info('El trabajador de la cola ejecutó un trabajo. Revisa si la notificación llegó.');

        } catch (\Throwable $e) {
            $this->error('¡ERROR FATAL CAPTURADO!');
            $this->error('Clase del Error: ' . get_class($e));
            $this->error('Mensaje: ' . $e->getMessage());
            $this->error('Archivo: ' . $e->getFile());
            $this->error('Línea: ' . $e->getLine());
            $this->line("\n--- Stack Trace ---\n");
            $this->line($e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}