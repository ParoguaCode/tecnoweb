<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { store as pagoStore } from '@/routes/pagos';
import Combobox from '@/components/ui/combobox/Combobox.vue';

const props = defineProps<{ plan: any, siguienteCuota: number, metodos: string[] }>();

const form = useForm({
    estado: 'pendiente',
    fechapago: '',
    metodopago: props.metodos[0] ?? 'efectivo',
    monto: props.plan.montoporcuota ?? 0,
    numerocuota: props.siguienteCuota ?? 1,
    referencia: '',
});

const submit = () => {
    const url = pagoStore({ planPago: props.plan.id }).url;
    form.post(url);
};
</script>

<template>
    <Head :title="`Nuevo Pago para Plan #${plan.id}`" />

    <AppLayout>
        <div class="p-4 max-w-3xl">
            <Card>
                <CardHeader>
                    <CardTitle>Registrar Pago</CardTitle>
                    <CardDescription>Plan #{{ plan.id }} · Orden #{{ plan.orden_trabajo_id }}</CardDescription>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="grid gap-4 md:grid-cols-2">
                        <div>
                            <Label>Estado</Label>
                            <Combobox v-model="form.estado" :items="['pendiente','en proceso','terminado'].map(e => ({ id: e, label: e }))" />
                            <InputError :message="form.errors.estado" />
                        </div>

                        <div>
                            <Label>Fecha de Pago</Label>
                            <Input type="date" v-model="form.fechapago" />
                            <InputError :message="form.errors.fechapago" />
                        </div>

                        <div>
                            <Label>Método de Pago</Label>
                            <Combobox v-model="form.metodopago" :items="metodos.map(m => ({ id: m, label: m }))" />
                            <InputError :message="form.errors.metodopago" />
                        </div>

                        <div>
                            <Label>Monto</Label>
                            <Input type="number" step="0.01" v-model="form.monto" />
                            <InputError :message="form.errors.monto" />
                        </div>

                        <div>
                            <Label>Número de Cuota</Label>
                            <Input type="number" min="1" v-model="form.numerocuota" />
                            <InputError :message="form.errors.numerocuota" />
                        </div>

                        <div class="md:col-span-2">
                            <Label>Referencia</Label>
                            <Input type="text" v-model="form.referencia" />
                            <InputError :message="form.errors.referencia" />
                        </div>

                        <div class="md:col-span-2 flex gap-4">
                            <Button :disabled="form.processing">Guardar Pago</Button>
                            <Button type="button" variant="outline" :disabled="form.processing" @click="$inertia.visit(`/plan-pagos/${plan.id}`)">Cancelar</Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
