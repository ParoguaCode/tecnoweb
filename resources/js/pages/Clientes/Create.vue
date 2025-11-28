<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as clientesIndex, create, store } from '@/routes/clientes';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onUnmounted, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

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
        title: 'Nuevo Cliente',
        href: create().url,
    },
];

const form = useForm({
    nombre: '',
    telefono: '',
    foto: null as File | null,
});

const submit = () => {
    form.post(store().url, {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};

const onFotoChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.foto = file;
};

const previewUrl = ref<string | null>(null);
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
            previewUrl.value = previousObjectUrl;
        } else {
            previewUrl.value = null;
        }
    },
);

onUnmounted(() => {
    if (previousObjectUrl) URL.revokeObjectURL(previousObjectUrl);
});
</script>

<template>
    <Head title="Nuevo Cliente" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Crear Nuevo Cliente</CardTitle>
                    <CardDescription>
                        Complete el formulario para registrar un nuevo cliente
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
                            <div v-if="previewUrl" class="mt-2">
                                <img :src="previewUrl!" alt="Preview" class="h-20 w-20 rounded-md object-cover border" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Guardando...' : 'Guardar Cliente' }}
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
                                    Guardado.
                                </p>
                            </Transition>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
