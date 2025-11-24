<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Modelo;

class ModeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modelos = [
            ['nombre' => 'Corolla'],
            ['nombre' => 'Civic'],
            ['nombre' => 'F-150'],
            ['nombre' => 'Silverado'],
            ['nombre' => 'Altima'],
        ];

        foreach ($modelos as $modelo) {
            Modelo::create($modelo);
        }
    }
}
