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
import { create, destroy, index as modelosIndex } from '@/routes/modelos';
import { Modelo, Paginacion, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Pencil, Plus, Search, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Props {
    modelos: Paginacion<Modelo>;
    terminosBusqueda?: string;
}

const props = defineProps<Props>();
const modelos = computed(() => props.modelos.data);
const metadatos = computed(() => props.modelos);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Modelos',
        href: modelosIndex().url,
    },
];

const deleteModelo = (modelo: Modelo) => {
    if (confirm(`¿Está seguro de eliminar el modelo "${modelo.nombre}"?`)) {
        router.delete(destroy(modelo.id).url);
    }
};

const buscar = ref(props.terminosBusqueda);
watch(
    buscar,
    debounce(
        (query) =>
            router.get('/modelos', { busqueda: query }, { preserveState: true }),
        500,
    ),
);
</script>

<template>
    <Head title="Modelos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle>Gestión de Modelos</CardTitle>
                        <CardDescription>
                            Administre los modelos de vehículos
                        </CardDescription>
                        <br>
                        <div class="flex w-full space-x-2">
                            <Search />
                            <Input
                                ref="inputRef"
                                type="search"
                                class="max-w-sm"
                                placeholder="Buscar..."
                                v-model="buscar"
                            />
                        </div>
                    </div>
                    <div>
                        
                    </div>
                    <div v-if="puede('modelo.crear')">
                        <Link :href="create().url">
                            <Button>
                                <Plus class="mr-2 h-4 w-4" />
                                Nuevo Modelo
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="metadatos.data.length === 0"
                        class="py-8 text-center text-muted-foreground"
                    >
                        No hay modelos registrados
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
                                    <th v-if="puede('modelo.editar')"
                                        class="h-12 px-4 text-right align-middle font-medium"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="modelo in modelos"
                                    :key="modelo.id"
                                    class="border-b transition-colors hover:bg-muted/50"
                                >
                                    <td class="p-4 align-middle">
                                        {{ modelo.id }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ modelo.nombre }}
                                    </td>
                                    <td v-if="puede('modelo.editar')" class="p-4 text-right align-middle">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="`/modelos/${modelo.id}/edit`"
                                            >
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                >
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <div v-if="puede('modelo.eliminar')">
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    @click="deleteModelo(modelo)"
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
