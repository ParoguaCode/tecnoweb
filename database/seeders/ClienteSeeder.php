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
            // +10 clientes adicionales
            ['nombre' => 'Sofía Ramírez', 'telefono' => '77890123'],
            ['nombre' => 'Diego Morales', 'telefono' => '78901234'],
            ['nombre' => 'Lucía Herrera', 'telefono' => '79012345'],
            ['nombre' => 'Mateo Castro', 'telefono' => '70123456'],
            ['nombre' => 'Valentina Flores', 'telefono' => '71234567'],
            ['nombre' => 'Andrés López', 'telefono' => '72222222'],
            ['nombre' => 'Mariana Silva', 'telefono' => '73333333'],
            ['nombre' => 'Ricardo Peña', 'telefono' => '74444444'],
            ['nombre' => 'Isabel Cruz', 'telefono' => '75555555'],
            ['nombre' => 'Fernando Díaz', 'telefono' => '76666666'],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
