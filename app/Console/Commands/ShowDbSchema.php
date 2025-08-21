<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShowDbSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:schema'; // Este es el nombre de nuestro nuevo comando

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Muestra el esquema de todas las tablas en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = reset($table);
            $this->info("--- TABLA: $tableName ---");
            $columns = DB::select("DESCRIBE `$tableName`");

            $headers = ['Campo', 'Tipo', 'Nulo', 'Llave', 'Por Defecto', 'Extra'];
            $rows = [];

            foreach ($columns as $column) {
                $rows[] = [
                    'Field' => $column->Field,
                    'Type' => $column->Type,
                    'Null' => $column->Null,
                    'Key' => $column->Key,
                    'Default' => $column->Default,
                    'Extra' => $column->Extra,
                ];
            }

            $this->table($headers, $rows);
            $this->line(''); // Añade un espacio entre tablas
        }

        return 0;
    }
}