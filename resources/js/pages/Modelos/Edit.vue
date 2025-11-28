<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as modelosIndex, edit, update } from '@/routes/modelos';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onUnmounted, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

interface Modelo {
    id: number;
    nombre: string;
    foto?: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    modelo: Modelo;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Modelos',
        href: modelosIndex().url,
    },
    {
        title: 'Editar Modelo',
        href: edit(props.modelo.id).url,
    },
];

const form = useForm({
    nombre: props.modelo.nombre,
    foto: null as File | null,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    }));
    form.post(update(props.modelo.id).url, { forceFormData: true });
};

const onFotoChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.foto = file;
};

const currentFotoUrl = computed(() =>
    props.modelo.foto ? `/storage/${props.modelo.foto}` : null,
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
    <Head title="Editar Modelo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Editar Modelo</CardTitle>
                    <CardDescription>
                        Modifique los datos del modelo "{{ modelo.nombre }}"
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="nombre">Nombre del Modelo</Label>
                            <Input
                                id="nombre"
                                v-model="form.nombre"
                                type="text"
                                placeholder="Ej: Corolla, Civic, F-150, etc."
                                required
                                :disabled="form.processing"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.nombre" class="mt-2" />
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
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Actualizando...' : 'Actualizar Modelo' }}
                            </Button>
                            
                            <Button 
                                type="button" 
                                variant="outline"
                                :disabled="form.processing"
                                @click="$inertia.visit(modelosIndex().url)"
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
