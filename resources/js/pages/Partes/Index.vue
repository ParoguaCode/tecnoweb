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
import { create, destroy, index as partesIndex } from '@/routes/partes';
import { Paginacion, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Pencil, Plus, Search, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Parte {
    id: number;
    nombre: string;
    motor_id: number;
    motor?: {
        id: number;
        numero_serie: string;
        marca?: {
            nombre: string;
        };
        modelo?: {
            nombre: string;
        };
    };
    created_at: string;
    updated_at: string;
}

interface Props {
    partes: Paginacion<Parte>;
    terminosBusqueda?: string;
}

const props = defineProps<Props>();
const partes = computed(() => props.partes.data);
const metadatos = computed(() => props.partes);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Partes',
        href: partesIndex().url,
    },
];

const deleteParte = (parte: Parte) => {
    if (confirm(`¿Está seguro de eliminar la parte "${parte.nombre}"?`)) {
        router.delete(destroy(parte.id).url);
    }
};

const buscar = ref(props.terminosBusqueda);
watch(
    buscar,
    debounce(
        (query) =>
            router.get('/partes', { busqueda: query }, { preserveState: true }),
        500,
    ),
);
</script>

<template>
    <Head title="Partes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle>Gestión de Partes</CardTitle>
                        <CardDescription>
                            Administre las partes de los motores
                        </CardDescription>
                        <br>
                        <div class="flex w-full space-x-2">
                            <Search />
                            <Input
                                ref="inputRef"
                                type="search"
                                class="max-w-sm"
                                placeholder="Buscar por nombre o motor..."
                                v-model="buscar"
                            />
                        </div>
                    </div>
                    <div>
                        
                    </div>
                    <div v-if="puede('parte.crear')">
                        <Link :href="create().url">
                            <Button>
                                <Plus class="mr-2 h-4 w-4" />
                                Nueva Parte
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="metadatos.data.length === 0"
                        class="py-8 text-center text-muted-foreground"
                    >
                        No hay partes registradas
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
                                        Nombre
                                    </th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        Motor
                                    </th>
                                    <!--th
                                        class="h-12 px-4 text-left align-middle font-medium"
                                    >
                                        Marca/Modelo
                                    </th-->
                                    <th v-if="puede('parte.editar')"
                                        class="h-12 px-4 text-right align-middle font-medium"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="parte in partes"
                                    :key="parte.id"
                                    class="border-b transition-colors hover:bg-muted/50"
                                >
                                    <td class="p-4 align-middle">
                                        {{ parte.id }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ parte.nombre }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ parte.motor?.numero_serie || 'N/A' }}
                                    </td>
                                    <!--td class="p-4 align-middle">
                                        <span v-if="parte.motor?.marca && parte.motor?.modelo">
                                            {{ parte.motor.marca.nombre }} / {{ parte.motor.modelo.nombre }}
                                        </span>
                                        <span v-else class="text-muted-foreground">N/A</span>
                                    </td-->
                                    <td v-if="puede('parte.editar')" class="p-4 text-right align-middle">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="`/partes/${parte.id}/edit`"
                                            >
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                >
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <div v-if="puede('parte.eliminar')">
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    @click="deleteParte(parte)"
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
