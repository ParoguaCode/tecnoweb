<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as clientesIndex, edit, update } from '@/routes/clientes';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

interface Cliente {
    id: number;
    nombre: string;
    telefono: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    cliente: Cliente;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Clientes',
        href: clientesIndex().url,
    },
    {
        title: 'Editar Cliente',
        href: edit(props.cliente.id).url,
    },
];

const form = useForm({
    nombre: props.cliente.nombre,
    telefono: props.cliente.telefono,
});

const submit = () => {
    form.put(update(props.cliente.id).url);
};
</script>

<template>
    <Head title="Editar Cliente" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Editar Cliente</CardTitle>
                    <CardDescription>
                        Modifique los datos del cliente "{{ cliente.nombre }}"
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="nombre">Nombre del Cliente</Label>
                            <Input
                                id="nombre"
                                v-model="form.nombre"
                                type="text"
                                placeholder="Ej: Juan Pérez"
                                required
                                :disabled="form.processing"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.nombre" class="mt-2" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="telefono">Teléfono</Label>
                            <Input
                                id="telefono"
                                v-model="form.telefono"
                                type="text"
                                placeholder="Ej: 72345678"
                                required
                                :disabled="form.processing"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.telefono" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Actualizando...' : 'Actualizar Cliente' }}
                            </Button>
                            
                            <Button 
                                type="button" 
                                variant="outline"
                                :disabled="form.processing"
                                @click="$inertia.visit(clientesIndex().url)"
                            >
                                Cancelar
                            </Button>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p
                                    v-show="form.recentlySuccessful"
                                    class="text-sm text-muted-foreground"
                                >
                                    Actualizado.
                                </p>
                            </Transition>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
