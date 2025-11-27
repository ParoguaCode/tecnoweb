<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

const props = defineProps({
    orden: Object,
});

const form = useForm({
    descripcion: '',
    fecha: '',
    // default must match DB enum values
    estado: 'registrada',
});

const submit = () => {
    // Use an explicit URL to avoid runtime problems with the `route()` helper
    // (some environments may not expose the helper or return unexpected values).
    const url = `/orden-trabajos/${props.orden.id}/incidencias`;

    form.post(url, {
        onStart: () => console.debug('[Incidencia] submit start', { url, orden: props.orden?.id }),
        onSuccess: () => console.debug('[Incidencia] created — redirecting back'),
        onError: (errors) => console.warn('[Incidencia] validation errors', errors),
        onFinish: () => console.debug('[Incidencia] submit finished'),
    });
};
</script>

<template>
    <Head title="Registrar Incidencia" />

    <AppLayout>
        <div class="p-4 max-w-3xl">

            <Card>
                <CardHeader>
                    <CardTitle>Registrar Incidencia</CardTitle>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="space-y-5">

                        <div>
                            <Label>Descripción</Label>
                            <Input type="text" v-model="form.descripcion" placeholder="Detalle de la incidencia" />
                            <InputError :message="form.errors.descripcion" />
                        </div>

                        <div>
                            <Label>Fecha</Label>
                            <Input type="date" v-model="form.fecha" />
                            <InputError :message="form.errors.fecha" />
                        </div>

                        <div>
                            <Label>Estado</Label>
                            <select v-model="form.estado" class="border rounded p-2">
                                <option value="registrada">Registrada</option>
                                <option value="en revisión">En Revisión</option>
                                <option value="resuelta">Resuelta</option>
                            </select>
                        </div>

                        <div class="flex gap-4">
                            <Button :disabled="form.processing">
                                Registrar
                            </Button>

                            <Button type="button" variant="outline"
                                @click="$inertia.visit(`/orden-trabajos/${orden.id}`)">
                                Cancelar
                            </Button>
                        </div>

                    </form>
                </CardContent>
            </Card>

        </div>
    </AppLayout>
</template>
