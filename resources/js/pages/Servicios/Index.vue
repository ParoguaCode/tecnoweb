<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

defineProps({
    servicios: Object, // paginado
});
</script>

<template>
    <Head title="Servicios" />

    <AppLayout>
        <div class="p-6 space-y-4">

            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">Servicios</h1>

                <Button>
                    <Link href="/servicios/create" class="flex items-center gap-2">
                        <Plus class="h-4 w-4" /> Nuevo Servicio
                    </Link>
                </Button>
            </div>

            <!-- Tabla -->
            <div class="border rounded-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">ID</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Costo</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="s in servicios.data" :key="s.id" class="border-b">
                            <td class="p-2">{{ s.id }}</td>
                            <td class="p-2">{{ s.nombre }}</td>
                            <td class="p-2">{{ s.costo }} Bs</td>
                            <td class="p-2 flex gap-2">

                                <Link 
                                    :href="`/servicios/${s.id}/edit`"
                                    class="p-2 bg-blue-500 text-white rounded"
                                >
                                    <Pencil class="h-4 w-4" />
                                </Link>

                                <Link 
                                    :href="`/servicios/${s.id}`"
                                    method="delete"
                                    as="button"
                                    class="p-2 bg-red-500 text-white rounded"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Link>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AppLayout>
</template>
