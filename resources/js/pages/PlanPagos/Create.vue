<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { store as planStore } from '@/routes/plan-pagos';
import Combobox from '@/components/ui/combobox/Combobox.vue';

const props = defineProps<{ orden: any }>();

const estados = ['pendiente', 'en proceso', 'terminado']

const form = useForm({
    estado: 'pendiente',
    fechainicio: '',
    fechafin: '',
    montoporcuota: '',
    numerocuotas: 1,
    observacion: '',
});

const submit = () => {
    const url = planStore({ ordenTrabajo: props.orden.id }).url;
    form.post(url);
};

const montoTotalOrden = props.orden?.total ?? 0;
</script>

<template>
    <Head title="Crear Plan de Pagos" />

    <AppLayout>
        <div class="p-4 max-w-3xl">
            <Card>
                <CardHeader>
                    <CardTitle>Plan de Pagos para Orden #{{ orden.id }}</CardTitle>
                    <CardDescription>Complete los datos del plan</CardDescription>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="grid gap-4 md:grid-cols-2">
                        <div>
                            <Label>Estado</Label>
                            <Combobox v-model="form.estado" :items="estados.map(e => ({ id: e, label: e }))" />
                            <InputError :message="form.errors.estado" />
                        </div>

                        <div>
                            <Label>Fecha Inicio</Label>
                            <Input type="date" v-model="form.fechainicio" />
                            <InputError :message="form.errors.fechainicio" />
                        </div>

                        <div>
                            <Label>Fecha Fin</Label>
                            <Input type="date" v-model="form.fechafin" />
                            <InputError :message="form.errors.fechafin" />
                        </div>

                        <div>
                            <Label>Monto por Cuota</Label>
                            <Input type="number" step="0.01" v-model="form.montoporcuota" />
                            <InputError :message="form.errors.montoporcuota" />
                        </div>

                        <div>
                            <Label>Número de Cuotas</Label>
                            <Input type="number" min="1" v-model="form.numerocuotas" />
                            <InputError :message="form.errors.numerocuotas" />
                        </div>

                        <div>
                            <Label>Monto Total (de la Orden)</Label>
                            <Input type="number" :model-value="montoTotalOrden" readonly />
                            <p class="text-xs text-muted-foreground">Este valor se toma de la Orden de Trabajo y no se edita aquí.</p>
                        </div>

                        <div class="md:col-span-2">
                            <Label>Observación</Label>
                            <Input type="text" v-model="form.observacion" />
                            <InputError :message="form.errors.observacion" />
                        </div>

                        <div class="md:col-span-2 flex gap-4">
                            <Button :disabled="form.processing">Crear Plan</Button>
                            <Button type="button" variant="outline" :disabled="form.processing" @click="$inertia.visit(`/orden-trabajos/${orden.id}`)">Cancelar</Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
