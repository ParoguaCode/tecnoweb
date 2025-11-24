<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Toyota'],
            ['nombre' => 'Honda'],
            ['nombre' => 'Ford'],
            ['nombre' => 'Chevrolet'],
            ['nombre' => 'Nissan'],
        ];

        foreach ($marcas as $marca) {
            Marca::create($marca);
        }
    }
}
