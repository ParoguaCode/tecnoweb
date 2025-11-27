<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index as serviciosIndex, store as serviciosStore } from '@/routes/servicios';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

import { Head, useForm } from "@inertiajs/vue3";
import { Card, CardHeader, CardTitle, CardContent, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/InputError.vue";

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Dashboard", href: dashboard().url },
    { title: "Servicios", href: serviciosIndex().url },
    { title: "Crear Servicio", href: "#" },
];

const form = useForm({
    nombre: "",
    descripcion: "",
    costo: null,
});

const submit = () => {
    form.post(serviciosStore().url, {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Crear Servicio" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 max-w-xl">

            <Card>
                <CardHeader>
                    <CardTitle>Crear Servicio</CardTitle>
                    <CardDescription>Complete los datos del nuevo servicio.</CardDescription>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">

                        <div>
                            <Label>Nombre</Label>
                            <Input v-model="form.nombre" type="text" />
                            <InputError :message="form.errors.nombre" />
                        </div>

                        <div>
                            <Label>Descripción</Label>
                            <Input v-model="form.descripcion" type="text" />
                            <InputError :message="form.errors.descripcion" />
                        </div>

                        <div>
                            <Label>Costo (Bs)</Label>
                            <Input v-model.number="form.costo" type="number" step="0.01" />
                            <InputError :message="form.errors.costo" />
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit">Guardar</Button>
                            <Button type="button" variant="outline" @click="$inertia.visit(serviciosIndex().url)">
                                Cancelar
                            </Button>
                        </div>

                    </form>
                </CardContent>
            </Card>

        </div>
    </AppLayout>
</template>
