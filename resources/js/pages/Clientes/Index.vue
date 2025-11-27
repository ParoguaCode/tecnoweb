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
import { create, destroy, index as clientesIndex } from '@/routes/clientes';
import { Cliente, Paginacion, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Pencil, Plus, Search, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';


interface Props {
    clientes: Paginacion<Cliente>;
    terminosBusqueda?: string;
}

const props = defineProps<Props>();
const clientes = computed(() => props.clientes.data);
const metadatos = computed(() => props.clientes);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Clientes',
        href: clientesIndex().url,
    },
];

const deleteCliente = (cliente: Cliente) => {
    if (confirm(`¿Está seguro de eliminar al cliente "${cliente.nombre}"?`)) {
        router.delete(destroy(cliente.id).url);
    }
};

const buscar = ref(props.terminosBusqueda);
watch(
    buscar,
    debounce(
        (query) =>
            router.get('/clientes', { busqueda: query }, { preserveState: true }),
        500,
    ),
);
</script>

<template>
    <Head title="Clientes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle>Gestión de Clientes</CardTitle>
                        <CardDescription>
                            Administre los clientes del sistema
                        </CardDescription>
                        <br>
                        <div class="flex w-full space-x-2">
                            <Search />
                            <Input
                                ref="inputRef"
                                type="search"
                                class="max-w-sm"
                                placeholder="Buscar por nombre o teléfono..."
                                v-model="buscar"
                            />
                        </div>
                    </div>
                    <div>
                        
                    </div>
                    <div v-if="puede('cliente.crear')">
                        <Link :href="create().url">
                            <Button>
                                <Plus class="mr-2 h-4 w-4" />
                                Nuevo Cliente
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="metadatos.data.length === 0"
                        class="py-8 text-center text-muted-foreground"
                    >
                        No hay clientes registrados
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
                                        Teléfono
                                    </th>
                                    <th v-if="puede('cliente.crear')"
                                        class="h-12 px-4 text-right align-middle font-medium"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="cliente in clientes"
                                    :key="cliente.id"
                                    class="border-b transition-colors hover:bg-muted/50"
                                >
                                    <td class="p-4 align-middle">
                                        {{ cliente.id }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ cliente.nombre }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        {{ cliente.telefono }}
                                    </td>
                                    <td v-if="puede('cliente.crear')" class="p-4 text-right align-middle">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="`/clientes/${cliente.id}/edit`"
                                            >
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                >
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <div v-if="puede('cliente.eliminar')">
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    @click="deleteCliente(cliente)"
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
