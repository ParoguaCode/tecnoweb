<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as marcasIndex, edit, update } from '@/routes/marcas';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

interface Marca {
    id: number;
    nombre: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    marca: Marca;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Marcas',
        href: marcasIndex().url,
    },
    {
        title: 'Editar Marca',
        href: edit(props.marca.id).url,
    },
];

const form = useForm({
    nombre: props.marca.nombre,
});

const submit = () => {
    form.put(update(props.marca.id).url);
};
</script>

<template>
    <Head title="Editar Marca" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Editar Marca</CardTitle>
                    <CardDescription>
                        Modifique los datos de la marca "{{ marca.nombre }}"
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="nombre">Nombre de la Marca</Label>
                            <Input
                                id="nombre"
                                v-model="form.nombre"
                                type="text"
                                placeholder="Ej: Toyota, Ford, etc."
                                required
                                :disabled="form.processing"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.nombre" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Actualizando...' : 'Actualizar Marca' }}
                            </Button>
                            
                            <Button 
                                type="button" 
                                variant="outline"
                                :disabled="form.processing"
                                @click="$inertia.visit(marcasIndex().url)"
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