<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            ['nombre' => 'Juan Pérez', 'telefono' => '72345678'],
            ['nombre' => 'María González', 'telefono' => '73456789'],
            ['nombre' => 'Carlos Rodríguez', 'telefono' => '74567890'],
            ['nombre' => 'Ana Martínez', 'telefono' => '75678901'],
            ['nombre' => 'Luis Fernández', 'telefono' => '76789012'],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
