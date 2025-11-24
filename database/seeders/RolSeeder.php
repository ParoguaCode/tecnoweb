<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Propietario'],
            ['nombre' => 'Mecanico'],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
