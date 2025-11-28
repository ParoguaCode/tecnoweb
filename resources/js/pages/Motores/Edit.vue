<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as motoresIndex, edit, update } from '@/routes/motores';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, Motor, Marca, Modelo } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onUnmounted, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Textarea from '@/components/ui/textarea.vue';
import InputError from '@/components/InputError.vue';

interface Props {
    motor: Motor;
    marcas: Marca[];
    modelos: Modelo[];
}

const props = defineProps<Props>();

// ✔ Variables reactivas (se mantiene la reactividad y se elimina el warning)
const motor = props.motor;
const marcas = props.marcas;
const modelos = props.modelos;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Motores',
        href: motoresIndex().url,
    },
    {
        title: 'Editar Motor',
        href: edit(motor.id).url,
    },
];

const form = useForm({
    numero_serie: motor.numero_serie,
    anio: motor.anio,
    descripcion: motor.descripcion || '',
    marca_id: motor.marca_id.toString(),
    modelo_id: motor.modelo_id.toString(),
    foto: null as File | null,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    }));
    form.post(update(motor.id).url, { forceFormData: true });
};

const onFotoChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.foto = file;
};

const currentFotoUrl = computed(() =>
    motor.foto ? `/storage/${motor.foto}` : null,
);

const newPreviewUrl = ref<string | null>(null);
let previousObjectUrl: string | null = null;

watch(
    () => form.foto,
    (file) => {
        if (previousObjectUrl) {
            URL.revokeObjectURL(previousObjectUrl);
            previousObjectUrl = null;
        }
        if (typeof window !== 'undefined' && file instanceof File) {
            previousObjectUrl = URL.createObjectURL(file);
            newPreviewUrl.value = previousObjectUrl;
        } else {
            newPreviewUrl.value = null;
        }
    },
);

onUnmounted(() => {
    if (previousObjectUrl) URL.revokeObjectURL(previousObjectUrl);
});
</script>

<template>
    <Head title="Editar Motor" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Editar Motor</CardTitle>
                    <CardDescription>
                        Modifique los datos del motor "{{ motor.numero_serie }}"
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="numero_serie">Número de Serie</Label>
                                <Input
                                    id="numero_serie"
                                    v-model="form.numero_serie"
                                    type="text"
                                    required
                                    :disabled="form.processing"
                                    placeholder="Ej: TOY-2024-001"
                                />
                                <InputError :message="form.errors.numero_serie" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="anio">Año</Label>
                                <Input
                                    id="anio"
                                    v-model.number="form.anio"
                                    type="number"
                                    required
                                    :disabled="form.processing"
                                    :min="1900"
                                    :max="new Date().getFullYear() + 1"
                                    placeholder="Ej: 2024"
                                />
                                <InputError :message="form.errors.anio" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="marca_id">Marca</Label>
                                <select
                                    id="marca_id"
                                    v-model="form.marca_id"
                                    required
                                    :disabled="form.processing"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                >
                                    <option value="">Seleccione una marca</option>
                                    <option
                                        v-for="marca in marcas"
                                        :key="marca.id"
                                        :value="marca.id.toString()"
                                    >
                                        {{ marca.nombre }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.marca_id" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="modelo_id">Modelo</Label>
                                <select
                                    id="modelo_id"
                                    v-model="form.modelo_id"
                                    required
                                    :disabled="form.processing"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                >
                                    <option value="">Seleccione un modelo</option>
                                    <option
                                        v-for="modelo in modelos"
                                        :key="modelo.id"
                                        :value="modelo.id.toString()"
                                    >
                                        {{ modelo.nombre }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.modelo_id" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="descripcion">Descripción (Opcional)</Label>
                            <Textarea
                                id="descripcion"
                                v-model="form.descripcion"
                                rows="3"
                                :disabled="form.processing"
                                placeholder="Ej: Motor 1.8L 4 cilindros..."
                            />
                            <InputError :message="form.errors.descripcion" class="mt-2" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="foto">Foto (Opcional)</Label>
                            <input
                                id="foto"
                                type="file"
                                accept="image/*"
                                @change="onFotoChange"
                                :disabled="form.processing"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            />
                            <InputError :message="form.errors.foto" class="mt-2" />
                            <div class="mt-2 flex items-center gap-4">
                                <div v-if="currentFotoUrl">
                                    <img :src="currentFotoUrl!" alt="Actual" class="h-20 w-20 rounded-md object-cover border" />
                                </div>
                                <div v-if="newPreviewUrl">
                                    <img :src="newPreviewUrl!" alt="Nueva" class="h-20 w-20 rounded-md object-cover border" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Actualizando...' : 'Actualizar Motor' }}
                            </Button>

                            <Button
                                type="button"
                                variant="outline"
                                :disabled="form.processing"
                                @click="$inertia.visit(motoresIndex().url)"
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
