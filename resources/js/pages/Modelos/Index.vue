<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as modelosIndex, create, destroy } from '@/routes/modelos';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';

interface Modelo {
    id: number;
    nombre: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    modelos: Modelo[];
}

defineProps<Props>();

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
                    </div>
                    <Link :href="create().url">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            Nuevo Modelo
                        </Button>
                    </Link>
                </CardHeader>
                <CardContent>
                    <div v-if="modelos.length === 0" class="py-8 text-center text-muted-foreground">
                        No hay modelos registrados
                    </div>
                    <div v-else class="rounded-md border">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b bg-muted/50">
                                    <th class="h-12 px-4 text-left align-middle font-medium">
                                        ID
                                    </th>
                                    <th class="h-12 px-4 text-left align-middle font-medium">
                                        Nombre
                                    </th>
                                    <th class="h-12 px-4 text-right align-middle font-medium">
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
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="`/modelos/${modelo.id}/edit`">
                                                <Button variant="outline" size="sm">
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <Button 
                                                variant="destructive" 
                                                size="sm"
                                                @click="deleteModelo(modelo)"
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
        </div>
    </AppLayout>
</template>
