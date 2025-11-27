<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import PaginationLinks from '@/components/global/PaginationLinks.vue';

import { dashboard } from '@/routes';
import { 
    index as otIndex, 
    create as otCreate,
    destroy as otDestroy,
} from '@/routes/orden-trabajos';

import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

import { Pencil, Plus, Search, Trash2, Eye } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { debounce } from 'lodash';

// Props desde el controlador
interface OrdenTrabajo {
    id: number;
    descripcion: string;
    fechainicio: string;
    estado: string;
    cliente: { nombre: string };
    usuario: { name: string };
    motor: { numero_serie: string };
    total: number;
}

interface Props {
    ordenes: {
        data: OrdenTrabajo[];
        total: number;
        current_page: number;
        per_page: number;
        last_page: number;
    };
    terminosBusqueda?: string;
}

const props = defineProps<Props>();

const ordenes = computed(() => props.ordenes.data);
const paginator = computed(() => props.ordenes);

// -------------------------------
// Breadcrumbs
// -------------------------------
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Órdenes de Trabajo', href: otIndex().url },
];

// -------------------------------
// Buscador
// -------------------------------
const buscar = ref(props.terminosBusqueda);

watch(
    buscar,
    debounce((query) => {
        router.get('/orden-trabajos', { busqueda: query }, { preserveState: true });
    }, 500)
);

// -------------------------------
// Eliminar
// -------------------------------
const deleteOrden = (orden: OrdenTrabajo) => {
    if (confirm(`¿Eliminar la orden #${orden.id}?`)) {
        router.delete(otDestroy(orden.id).url);
    }
};
</script>

<template>
    <Head title="Órdenes de Trabajo" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex flex-col gap-4 p-4">

            <!-- CARD PRINCIPAL -->
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle>Órdenes de Trabajo</CardTitle>
                        <CardDescription>Gestione todas las órdenes registradas</CardDescription>

                        <br>

                        <!-- Buscador -->
                        <div class="flex w-full space-x-2 items-center">
                            <Search />
                            <Input 
                                type="search"
                                placeholder="Buscar..."
                                class="max-w-sm"
                                v-model="buscar"
                            />
                        </div>
                    </div>

                    <Link :href="otCreate().url">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            Nueva Orden
                        </Button>
                    </Link>
                </CardHeader>

                <CardContent>

                    <!-- SI NO HAY DATOS -->
                    <div v-if="ordenes.length === 0"
                        class="py-8 text-center text-muted-foreground">
                        No hay órdenes de trabajo registradas.
                    </div>

                    <!-- TABLA -->
                    <div v-else class="rounded-md border overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b bg-muted/50">
                                    <th class="h-12 px-4 text-left font-medium">ID</th>
                                    <th class="h-12 px-4 text-left font-medium">Cliente</th>
                                    <th class="h-12 px-4 text-left font-medium">Descripción</th>
                                    <th class="h-12 px-4 text-left font-medium">Estado</th>
                                    <th class="h-12 px-4 text-left font-medium">Motor</th>
                                    <th class="h-12 px-4 text-left font-medium">Total</th>
                                    <th class="h-12 px-4 text-right font-medium">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr 
                                    v-for="ot in ordenes"
                                    :key="ot.id"
                                    class="border-b hover:bg-muted/50 transition"
                                >
                                    <td class="p-4">{{ ot.id }}</td>
                                    <td class="p-4">{{ ot.cliente?.nombre }}</td>
                                    <td class="p-4">{{ ot.descripcion }}</td>
                                    <td class="p-4 capitalize">{{ ot.estado }}</td>
                                    <td class="p-4">{{ ot.motor?.numero_serie }}</td>
                                    <td class="p-4">{{ ot.total }} Bs</td>

                                    <td class="p-4 text-right">
                                        <div class="flex justify-end gap-2">

                                            <!-- Ver -->
                                            <Link :href="`/orden-trabajos/${ot.id}`">
                                                <Button variant="outline" size="sm">
                                                    <Eye class="h-4 w-4" />
                                                </Button>
                                            </Link>

                                            <!-- Editar -->
                                            <Link :href="`/orden-trabajos/${ot.id}/edit`">
                                                <Button variant="outline" size="sm">
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>

                                            <!-- Eliminar -->
                                            <Button 
                                                variant="destructive" 
                                                size="sm"
                                                @click="deleteOrden(ot)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </CardContent>
            </Card>

            <!-- PAGINACIÓN -->
            <PaginationLinks :paginator="paginator" />

        </div>

    </AppLayout>
</template>
