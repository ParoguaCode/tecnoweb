<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { store as facturaStore } from '@/routes/facturas';

const props = defineProps<{ pago: any }>();

const form = useForm({
    descripcion: '',
    estado: 'emitida',
    fechaemision: '',
    montototal: props.pago.monto ?? 0,
    nroautorizacion: '',
    numerofactura: '',
});

const submit = () => {
    const url = facturaStore({ pago: props.pago.id }).url;
    form.post(url);
};
</script>

<template>
    <Head :title="`Emitir Factura del Pago #${pago.id}`" />

    <AppLayout>
        <div class="p-4 max-w-3xl">
            <Card>
                <CardHeader>
                    <CardTitle>Emitir Factura</CardTitle>
                    <CardDescription>Plan #{{ pago.plan_pago_id }} · Pago #{{ pago.id }}</CardDescription>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <Label>Descripción</Label>
                            <Input type="text" v-model="form.descripcion" />
                            <InputError :message="form.errors.descripcion" />
                        </div>

                        <div>
                            <Label>Estado</Label>
                            <Input type="text" v-model="form.estado" />
                            <InputError :message="form.errors.estado" />
                        </div>

                        <div>
                            <Label>Fecha de Emisión</Label>
                            <Input type="date" v-model="form.fechaemision" />
                            <InputError :message="form.errors.fechaemision" />
                        </div>

                        <div>
                            <Label>Monto Total</Label>
                            <Input type="number" step="0.01" v-model="form.montototal" readonly />
                            <InputError :message="form.errors.montototal" />
                        </div>

                        <div>
                            <Label>Nro. Autorización</Label>
                            <Input type="text" v-model="form.nroautorizacion" />
                            <InputError :message="form.errors.nroautorizacion" />
                        </div>

                        <div>
                            <Label>Número de Factura</Label>
                            <Input type="text" v-model="form.numerofactura" />
                            <InputError :message="form.errors.numerofactura" />
                        </div>

                        <div class="md:col-span-2 flex gap-4">
                            <Button :disabled="form.processing">Emitir</Button>
                            <Button type="button" variant="outline" :disabled="form.processing" @click="$inertia.visit(`/plan-pagos/${pago.plan_pago_id}`)">Cancelar</Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

