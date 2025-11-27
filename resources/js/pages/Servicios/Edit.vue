<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { index as serviciosIndex, update as serviciosUpdate } from "@/routes/servicios";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";

import { Head, useForm, Link } from "@inertiajs/vue3";

import { Card, CardHeader, CardTitle, CardDescription, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/InputError.vue";

const props = defineProps({
    servicio: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Dashboard", href: dashboard().url },
    { title: "Servicios", href: serviciosIndex().url },
    { title: `Editar Servicio`, href: "#" },
];

const form = useForm({
    nombre: props.servicio.nombre ?? "",
    descripcion: props.servicio.descripcion ?? "",
    costo: props.servicio.costo ?? "",
});

const submit = () => {
    form.put(serviciosUpdate(props.servicio.id).url);
};
</script>

<template>
    <Head title="Editar Servicio" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 max-w-2xl">

            <Card>
                <CardHeader>
                    <CardTitle>Editar Servicio</CardTitle>
                    <CardDescription>
                        Modifique los datos del servicio seleccionado.
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">

                        <!-- Nombre -->
                        <div>
                            <Label>Nombre</Label>
                            <Input v-model="form.nombre" type="text" />
                            <InputError :message="form.errors.nombre" />
                        </div>

                        <!-- Descripción -->
                        <div>
                            <Label>Descripción</Label>
                            <Input v-model="form.descripcion" type="text" />
                            <InputError :message="form.errors.descripcion" />
                        </div>

                        <!-- Costo -->
                        <div>
                            <Label>Costo (Bs)</Label>
                            <Input v-model="form.costo" type="number" step="0.01" />
                            <InputError :message="form.errors.costo" />
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                Guardar Cambios
                            </Button>

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
