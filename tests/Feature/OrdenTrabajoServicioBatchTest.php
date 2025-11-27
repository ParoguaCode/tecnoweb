<?php

use App\Models\Cliente;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Motor;
use App\Models\OrdenTrabajo;
use App\Models\Servicio;
use App\Models\User;
use App\Models\Rol;

it('can add multiple services to an order in a single request', function () {
    $role = Rol::create(['nombre' => 'TestRole']);
    $user = User::factory()->create(['rol_id' => $role->id]);

    $cliente = Cliente::create(['nombre' => 'Cliente Test', 'telefono' => '123456']);
    $marca = Marca::create(['nombre' => 'MarcaTest']);
    $modelo = Modelo::create(['nombre' => 'ModeloTest']);

    $motor = Motor::create([
        'numero_serie' => 'ABC123',
        'anio' => 2020,
        'descripcion' => 'Motor Test',
        'marca_id' => $marca->id,
        'modelo_id' => $modelo->id,
    ]);

    $orden = OrdenTrabajo::create([
        'fechainicio' => now(),
        'fechafin' => null,
        'descripcion' => 'Orden test',
        'total' => 0,
        'estado' => 'pendiente',
        'cliente_id' => $cliente->id,
        'usuario_id' => $user->id,
        'motor_id' => $motor->id,
    ]);

    $s1 = Servicio::create(['nombre' => 'S1', 'descripcion' => 's1', 'costo' => 100]);
    $s2 = Servicio::create(['nombre' => 'S2', 'descripcion' => 's2', 'costo' => 200]);

    $response = $this->actingAs($user)
        ->post("/orden-trabajos/{$orden->id}/servicios", [
            'servicio_ids' => [$s1->id, $s2->id],
            'cantidad' => 2,
        ]);

    $response->assertRedirect();

    // Check rows created / updated
    $this->assertDatabaseHas('orden_trabajo_servicios', [
        'orden_trabajo_id' => $orden->id,
        'servicio_id' => $s1->id,
        'cantidad' => 2,
        'precio' => 100,
        'subtotal' => 200,
    ]);

    $this->assertDatabaseHas('orden_trabajo_servicios', [
        'orden_trabajo_id' => $orden->id,
        'servicio_id' => $s2->id,
        'cantidad' => 2,
        'precio' => 200,
        'subtotal' => 400,
    ]);

    $orden->refresh();
    expect($orden->total)->toEqual(600);
});

it('can add multiple services with individual quantity and price in a single request', function () {
    $role = Rol::create(['nombre' => 'TestRoleB']);
    $user = User::factory()->create(['rol_id' => $role->id]);

    $cliente = Cliente::create(['nombre' => 'Cliente Test 2', 'telefono' => '000']);
    $marca = Marca::create(['nombre' => 'MarcaTest2']);
    $modelo = Modelo::create(['nombre' => 'ModeloTest2']);

    $motor = Motor::create([
        'numero_serie' => 'XYZ987',
        'anio' => 2021,
        'descripcion' => 'Motor Test B',
        'marca_id' => $marca->id,
        'modelo_id' => $modelo->id,
    ]);

    $orden = OrdenTrabajo::create([
        'fechainicio' => now(),
        'fechafin' => null,
        'descripcion' => 'Orden test 2',
        'total' => 0,
        'estado' => 'pendiente',
        'cliente_id' => $cliente->id,
        'usuario_id' => $user->id,
        'motor_id' => $motor->id,
    ]);

    $s1 = Servicio::create(['nombre' => 'S10', 'descripcion' => 's10', 'costo' => 50]);
    $s2 = Servicio::create(['nombre' => 'S20', 'descripcion' => 's20', 'costo' => 300]);

    // different quantities/prices for each
    $payload = [
        'servicios' => [
            ['servicio_id' => $s1->id, 'cantidad' => 3, 'precio' => 45],   // subtotal 135
            ['servicio_id' => $s2->id, 'cantidad' => 1, 'precio' => 300],  // subtotal 300
        ]
    ];

    $response = $this->actingAs($user)->post("/orden-trabajos/{$orden->id}/servicios", $payload);
    $response->assertRedirect();

    $this->assertDatabaseHas('orden_trabajo_servicios', [
        'orden_trabajo_id' => $orden->id,
        'servicio_id' => $s1->id,
        'cantidad' => 3,
        'precio' => 45,
        'subtotal' => 135,
    ]);

    $this->assertDatabaseHas('orden_trabajo_servicios', [
        'orden_trabajo_id' => $orden->id,
        'servicio_id' => $s2->id,
        'cantidad' => 1,
        'precio' => 300,
        'subtotal' => 300,
    ]);

    $orden->refresh();
    expect($orden->total)->toEqual(435);
});
