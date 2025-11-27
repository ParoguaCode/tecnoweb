<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

defineProps<{ factura: any }>();
</script>

<template>
    <Head :title="`Factura #${factura.numerofactura}`" />

    <AppLayout>
        <div class="p-6 space-y-4">
            <Card>
                <CardHeader class="flex items-center justify-between">
                    <div>
                        <CardTitle>Factura #{{ factura.numerofactura }}</CardTitle>
                        <CardDescription>
                            Pago #{{ factura.pago_id }} · Estado: {{ factura.estado }}
                        </CardDescription>
                    </div>
                    <Link :href="`/plan-pagos/${factura.pago?.plan_pago_id}`">
                        <Button variant="outline">Volver al Plan</Button>
                    </Link>
                </CardHeader>
                <CardContent class="grid md:grid-cols-2 gap-3">
                    <div>
                        <p><strong>Descripción:</strong> {{ factura.descripcion }}</p>
                        <p><strong>Fecha Emisión:</strong> {{ factura.fechaemision }}</p>
                        <p><strong>Nro. Autorización:</strong> {{ factura.nroautorizacion }}</p>
                    </div>
                    <div>
                        <p><strong>Monto Total:</strong> {{ factura.montototal }}</p>
                        <p><strong>Pago:</strong> {{ factura.pago_id }}</p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
