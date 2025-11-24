<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario1 = [
            'name' => 'Propietario Ejemplo',
            'email' => 'propietario@example.com',
            'password' => bcrypt('00000000'),
            'rol_id' => 1,
        ];

        User::create($usuario1);
    }
}
