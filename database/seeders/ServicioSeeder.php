<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Cambio de Aceite', 'descripcion' => 'Reemplazo de aceite y filtro', 'costo' => 120.00],
            ['nombre' => 'Revisión de Frenos', 'descripcion' => 'Inspección y ajuste de frenos', 'costo' => 200.00],
            ['nombre' => 'Cambio de Correa', 'descripcion' => 'Sustitución de correa de distribución', 'costo' => 450.00],
            ['nombre' => 'Alineación y Balanceo', 'descripcion' => 'Alineación de ruedas y balanceo', 'costo' => 180.00],
            ['nombre' => 'Cambio de Pastillas', 'descripcion' => 'Sustitución pastillas de freno', 'costo' => 160.00],
            ['nombre' => 'Reparación de Inyectores', 'descripcion' => 'Limpieza o reemplazo de inyectores', 'costo' => 300.00],
            ['nombre' => 'Revisión del Sistema Eléctrico', 'descripcion' => 'Diagnóstico y reparación de fallas eléctricas', 'costo' => 220.00],
            ['nombre' => 'Cambio de Bujías', 'descripcion' => 'Reemplazo de bujías y chequeo', 'costo' => 80.00],
            ['nombre' => 'Reparación de Motor', 'descripcion' => 'Trabajos de arreglo/mantenimiento del motor', 'costo' => 2500.00],
            ['nombre' => 'Rectificación de Motor', 'descripcion' => 'Rectificación parcial del motor', 'costo' => 4200.00],
            ['nombre' => 'Revisión de Suspensión', 'descripcion' => 'Inspección y reparación de suspensión', 'costo' => 260.00],
            ['nombre' => 'Cambio de Aceite Transmisión', 'descripcion' => 'Cambio de fluido de transmisión', 'costo' => 300.00],
            ['nombre' => 'Revisión Sistema de Escape', 'descripcion' => 'Inspección y reparación de escape', 'costo' => 140.00],
            ['nombre' => 'Cambio de Filtro de Combustible', 'descripcion' => 'Sustitución de filtro', 'costo' => 70.00],
            ['nombre' => 'Diagnóstico con Scanner', 'descripcion' => 'Diagnóstico electrónico con scanner', 'costo' => 90.00],
            ['nombre' => 'Reparación de Caja de Cambios', 'descripcion' => 'Reparación y ajuste de caja', 'costo' => 2300.00],
            ['nombre' => 'Mantenimiento Preventivo', 'descripcion' => 'Checkup completo y mantenimiento recomendado', 'costo' => 350.00],
            ['nombre' => 'Cambio de Correa Auxiliar', 'descripcion' => 'Sustitución de correa de accesorios', 'costo' => 110.00],
            ['nombre' => 'Servicio de Aire Acondicionado', 'descripcion' => 'Recarga y reparación AC', 'costo' => 210.00],
            ['nombre' => 'Reparación Sistema de Enfriamiento', 'descripcion' => 'Radiador/tuberías/bomba de agua', 'costo' => 600.00],
        ];

        foreach ($servicios as $s) {
            Servicio::create($s);
        }
    }
}
