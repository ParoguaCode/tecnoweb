<script setup lang="ts">
import PaginationLinks from '@/components/global/PaginationLinks.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Input from '@/components/ui/input/Input.vue';
import { puede } from '@/helpers/validarPermiso';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { create, destroy, index as motoresIndex } from '@/routes/motores';
import { Motor, Paginacion, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Pencil, Plus, Search, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';


interface Props {
    motores: Paginacion<Motor>;
    terminosBusqueda?: string;
}

const props = defineProps<Props>();
const motores = computed(() => props.motores.data);
const metadatos = computed(() => props.motores);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Motores',
        href: motoresIndex().url,
    },
];

const deleteMotor = (motor: Motor) => {
    if (confirm(`¿Está seguro de eliminar el motor "${motor.numero_serie}"?`)) {
        router.delete(destroy(motor.id).url);
    }
};

const buscar = ref(props.terminosBusqueda);
watch(
    buscar,
    debounce(
        (query) =>
            router.get('/motores', { busqueda: query }, { preserveState: true }),
        500,
    ),
);
</script>

<template>
    <Head title="Motores" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle>Gestión de Motores</CardTitle>
                        <CardDescription>
                            Administre los motores del sistema
                        </CardDescription>
                        <br>
                        <div class="flex w-full space-x-2">
                            <Search />
                            <Input
                                ref="inputRef"
                                type="search"
                                class="max-w-sm"
                                placeholder="Buscar por serie, marca, modelo..."
                                v-model="buscar"
                            />
                        </div>
                    </div>
                    <div>
                        
                    </div>
                    <div v-if="puede('motor.crear')">
                        <Link :href="create().url">
                            <Button>
                                <Plus class="mr-2 h-4 w-4" />
                                Nuevo Motor
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="metadatos.data.length === 0"
                        class="py-8 text-center text-muted-foreground"
                    >
                        No hay motores registrados
                    </div>
                    <div v-else class="rounded-md border">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b bg-muted/50">
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        ID
                                    </th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        N° Serie
                                    </th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        Marca
                                    </th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        Modelo
                                    </th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        Año
                                    </th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        Descripción
                                    </th>
                                    <th v-if="puede('motor.crear')"
                                        class="h-12 px-4 text-right align-middle font-medium"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="motor in motores"
                                    :key="motor.id"
                                    class="border-b transition-colors hover:bg-muted/50"
                                >
                                    <td class="p-4 align-middle">
                                        {{ motor.id }}
                                    </td>
                                    <td class="p-4 align-middle font-medium">
                                        {{ motor.numero_serie }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ motor.marca?.nombre || 'N/A' }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ motor.modelo?.nombre || 'N/A' }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ motor.anio }}
                                    </td>
                                    <td class="p-4 align-middle max-w-xs truncate">
                                        {{ motor.descripcion || '-' }}
                                    </td>
                                    <td v-if="puede('motor.crear')" class="p-4 text-right align-middle">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="`/motores/${motor.id}/edit`"
                                            >
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                >
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <div v-if="puede('motor.eliminar')">
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    @click="deleteMotor(motor)"
                                                >
                                                    <Trash2 class="h-4 w-4" />
                                                </Button>
                                                
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
            <PaginationLinks :paginator="metadatos" />
        </div>
    </AppLayout>
</template>
