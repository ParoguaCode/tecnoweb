<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as partesIndex, edit, update } from '@/routes/partes';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

interface Motor {
    id: number;
    numero_serie: string;
    marca?: {
        nombre: string;
    };
    modelo?: {
        nombre: string;
    };
}

interface Parte {
    id: number;
    nombre: string;
    motor_id: number;
    motor?: Motor;
    created_at: string;
    updated_at: string;
}

interface Props {
    parte: Parte;
    motores: Motor[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Partes',
        href: partesIndex().url,
    },
    {
        title: 'Editar Parte',
        href: edit(props.parte.id).url,
    },
];

const form = useForm({
    nombre: props.parte.nombre,
    motor_id: props.parte.motor_id.toString(),
});

const submit = () => {
    form.put(update(props.parte.id).url);
};
</script>

<template>
    <Head title="Editar Parte" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Editar Parte</CardTitle>
                    <CardDescription>
                        Modifique los datos de la parte "{{ parte.nombre }}"
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="nombre">Nombre de la Parte</Label>
                            <Input
                                id="nombre"
                                v-model="form.nombre"
                                type="text"
                                placeholder="Ej: Pistón, Bujía, Filtro de aceite..."
                                required
                                :disabled="form.processing"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.nombre" class="mt-2" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="motor_id">Motor</Label>
                            <select
                                id="motor_id"
                                v-model="form.motor_id"
                                required
                                :disabled="form.processing"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <option value="">Seleccione un motor</option>
                                <option
                                    v-for="motor in motores"
                                    :key="motor.id"
                                    :value="motor.id.toString()"
                                >
                                    {{ motor.numero_serie }} 
                                    <span v-if="motor.marca && motor.modelo">
                                        - {{ motor.marca.nombre }} / {{ motor.modelo.nombre }}
                                    </span>
                                </option>
                            </select>
                            <InputError :message="form.errors.motor_id" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Actualizando...' : 'Actualizar Parte' }}
                            </Button>
                            
                            <Button 
                                type="button" 
                                variant="outline"
                                :disabled="form.processing"
                                @click="$inertia.visit(partesIndex().url)"
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
