<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { 
    index as ordenIndex,
    show as ordenShow,
} from '@/routes/orden-trabajos';

import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

import { Head, router, useForm } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Plus, AlertTriangle, ArrowLeft, Pencil, Trash2 } from 'lucide-vue-next';
import { computed, watch } from 'vue';

interface ServicioPivot {
    id: number;
    nombre: string;
    pivot: {
        cantidad: number;
        precio: number;
        subtotal: number;
    };
}

interface Incidencia {
    id: number;
    descripcion: string;
    fecha: string | null;
    estado: string;
}

interface OrdenTrabajo {
    id: number;
    descripcion: string;
    fechainicio: string;
    fechafin: string | null;
    total: number;
    estado: string;
    cliente: { nombre: string };
    usuario: { name: string };
    motor: { numero_serie: string };
    servicios: ServicioPivot[];
    incidencias: Incidencia[];
}

interface ServicioCatalogo {
    id: number;
    nombre: string;
    costo: number;
}

const props = defineProps<{
    orden: OrdenTrabajo;
    serviciosCatalogo: ServicioCatalogo[];
}>();

// ==============================
//   Breadcrumbs
// ==============================
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Órdenes de Trabajo', href: ordenIndex().url },
    { title: `Orden #${props.orden.id}`, href: ordenShow(props.orden.id).url },
];

// ==============================
//   Formulario "Agregar Servicio"
// ==============================
const formServicio = useForm({
    servicio_id: '',
    cantidad: 1,
    precio: '',
    costo_sugerido: '' as string | number,
});

// cuando seleccionas un servicio, sugerimos el costo
watch(
    () => formServicio.servicio_id,
    (id) => {
        const serv = props.serviciosCatalogo.find(s => s.id === Number(id));
        if (serv) {
            formServicio.precio = serv.costo.toString();
            formServicio.costo_sugerido = serv.costo;
        } else {
            formServicio.precio = '';
            formServicio.costo_sugerido = '';
        }
    }
);

// calcular subtotal en frontend solo para mostrar
const subtotalCalculado = computed(() => {
    const cantidad = Number(formServicio.cantidad) || 0;
    const precio = Number(formServicio.precio) || 0;
    return cantidad * precio;
});

const enviarServicio = () => {
    formServicio.post(`/orden-trabajos/${props.orden.id}/servicios`, {
        onSuccess: () => {
            formServicio.reset('servicio_id', 'cantidad', 'precio', 'costo_sugerido');
        },
    });
};

const confirmDelete = (id: number) => {
    if (!confirm('¿Eliminar esta incidencia? Esta acción no se puede deshacer.')) return;

    router.delete(`/incidencias/${id}`, {
        preserveScroll: true,
        onStart: () => console.debug('[Incidencia] delete start', id),
        onSuccess: () => console.debug('[Incidencia] deleted', id),
        onError: (err) => console.warn('[Incidencia] delete error', err),
    });
};
</script>

<template>
    <Head :title="`Orden #${orden.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">

            <!-- BOTÓN VOLVER -->
            <Button 
                variant="outline"
                class="w-fit"
                @click="$inertia.visit(ordenIndex().url)"
            >
                <ArrowLeft class="mr-2 h-4 w-4" />
                Volver
            </Button>

            <!-- DATOS PRINCIPALES -->
            <Card class="shadow">
                <CardHeader>
                    <CardTitle>Orden de Trabajo #{{ orden.id }}</CardTitle>
                    <CardDescription>
                        Información general de la orden
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-3">
                    <p><strong>Descripción:</strong> {{ orden.descripcion }}</p>
                    <p><strong>Cliente:</strong> {{ orden.cliente.nombre }}</p>
                    <p><strong>Asignado a:</strong> {{ orden.usuario.name }}</p>
                    <p><strong>Motor:</strong> {{ orden.motor.numero_serie }}</p>

                    <p><strong>Fecha Inicio:</strong> {{ orden.fechainicio }}</p>
                    <p><strong>Fecha Fin:</strong> {{ orden.fechafin ?? 'Sin finalizar' }}</p>

                    <p><strong>Estado:</strong> 
                        <span class="px-2 py-1 rounded bg-blue-100 text-blue-800">
                            {{ orden.estado }}
                        </span>
                    </p>

                    <p><strong>Total:</strong> 
                        <span class="text-green-600 font-semibold">{{ orden.total }} Bs</span>
                    </p>
                </CardContent>
            </Card>

            <!-- =========================
                 SERVICIOS DE LA ORDEN
            ========================== -->
            <Card class="shadow">
                <CardHeader class="flex flex-row justify-between items-center">
                    <div>
                        <CardTitle>Servicios Agregados</CardTitle>
                        <CardDescription>
                            Lista de servicios asociados a esta orden
                        </CardDescription>
                    </div>
                </CardHeader>

                <CardContent class="space-y-6">
                    <!-- Tabla de servicios -->
                    <table class="w-full border rounded">
                        <thead>
                            <tr class="bg-muted/50 border-b">
                                <th class="text-left p-3">Servicio</th>
                                <th class="text-left p-3">Cantidad</th>
                                <th class="text-left p-3">Precio</th>
                                <th class="text-left p-3">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr 
                                v-for="serv in orden.servicios" 
                                :key="serv.id"
                                class="border-b hover:bg-muted/30"
                            >
                                <td class="p-3">{{ serv.nombre }}</td>
                                <td class="p-3">{{ serv.pivot.cantidad }}</td>
                                <td class="p-3">{{ serv.pivot.precio }} Bs</td>
                                <td class="p-3 font-semibold">{{ serv.pivot.subtotal }} Bs</td>
                            </tr>

                            <tr v-if="orden.servicios.length === 0">
                                <td colspan="4" class="p-4 text-center text-muted-foreground">
                                    No hay servicios agregados
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Formulario agregar servicio -->
                    <div class="border-t pt-4">
                        <h3 class="font-semibold mb-3 flex items-center gap-2">
                            <Plus class="h-4 w-4" />
                            Agregar servicio a esta orden
                        </h3>

                        <form @submit.prevent="enviarServicio" class="grid gap-4 md:grid-cols-4 items-end">
                            <!-- Servicio -->
                            <div class="md:col-span-2 flex flex-col gap-1">
                                <label class="text-sm font-medium">Servicio</label>
                                <select
                                    v-model="formServicio.servicio_id"
                                    class="border rounded p-2 text-sm"
                                    :disabled="formServicio.processing"
                                >
                                    <option value="">Seleccione un servicio</option>
                                    <option 
                                        v-for="s in serviciosCatalogo" 
                                        :key="s.id" 
                                        :value="s.id"
                                    >
                                        {{ s.nombre }} ({{ s.costo }} Bs)
                                    </option>
                                </select>
                            </div>

                            <!-- Cantidad -->
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium">Cantidad</label>
                                <input 
                                    type="number"
                                    min="1"
                                    v-model="formServicio.cantidad"
                                    class="border rounded p-2 text-sm"
                                    :disabled="formServicio.processing"
                                />
                            </div>

                            <!-- Precio -->
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium">Precio (Bs)</label>
                                <input 
                                    type="number"
                                    step="0.01"
                                    v-model="formServicio.precio"
                                    class="border rounded p-2 text-sm"
                                    :disabled="formServicio.processing"
                                />
                                <p v-if="formServicio.costo_sugerido" class="text-xs text-muted-foreground">
                                    Costo sugerido: {{ formServicio.costo_sugerido }} Bs
                                </p>
                            </div>

                            <!-- Subtotal y botón -->
                            <div class="md:col-span-4 flex flex-wrap items-center gap-4 mt-2">
                                <span class="text-sm">
                                    Subtotal calculado: 
                                    <strong>{{ subtotalCalculado }} Bs</strong>
                                </span>

                                <Button 
                                    type="submit"
                                    :disabled="formServicio.processing || !formServicio.servicio_id"
                                >
                                    {{ formServicio.processing ? 'Agregando…' : 'Agregar Servicio' }}
                                </Button>
                            </div>
                        </form>
                    </div>
                </CardContent>
            </Card>

            <!-- =========================
                 INCIDENCIAS
            ========================== -->
            <Card class="shadow">
                <CardHeader class="flex justify-between items-center">
                    <div>
                        <CardTitle>Incidencias Registradas</CardTitle>
                        <CardDescription>
                            Problemas o eventos registrados durante el trabajo
                        </CardDescription>
                    </div>

                    <Button
                        variant="secondary"
                        @click="$inertia.visit(`/orden-trabajos/${orden.id}/incidencias/create`)"
                    >
                        <AlertTriangle class="mr-2 h-4 w-4" />
                        Registrar Incidencia
                    </Button>
                </CardHeader>

                <CardContent>
                    <table class="w-full border rounded">
                        <thead>
                            <tr class="bg-muted/50 border-b">
                                <th class="text-left p-3">Descripción</th>
                                <th class="text-left p-3">Fecha</th>
                                <th class="text-left p-3">Estado</th>
                                <th class="text-left p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr 
                                v-for="inc in orden.incidencias" 
                                :key="inc.id"
                                class="border-b hover:bg-muted/30"
                            >
                                <td class="p-3">{{ inc.descripcion }}</td>
                                <td class="p-3">{{ inc.fecha ?? 'Sin fecha' }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800">
                                        {{ inc.estado }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Button variant="outline" size="sm"
                                            @click="$inertia.visit(`/incidencias/${inc.id}/edit`)" >
                                            <Pencil class="h-4 w-4" />
                                        </Button>

                                        <Button variant="destructive" size="sm" @click="confirmDelete(inc.id)">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="orden.incidencias.length === 0">
                                <td colspan="4" class="p-4 text-center text-muted-foreground">
                                    No hay incidencias registradas
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardContent>
            </Card>

        </div>
    </AppLayout>
</template>
