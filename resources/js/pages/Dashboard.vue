<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Users, Car, Clock } from 'lucide-vue-next';

interface MotorReciente {
    numero_serie: string;
    marca: string;
    modelo: string;
    anio: number;
}

interface ClienteReciente {
    nombre: string;
    telefono: string;
}

interface Props {
    totalClientes: number;
    totalMotores: number;
    motoresRecientes: MotorReciente[];
    clientesRecientes: ClienteReciente[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
            <!-- Tarjetas de totales -->
            <div class="grid gap-4 md:grid-cols-2">
                <!-- Total de Clientes -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">
                            Total de Clientes
                        </CardTitle>
                        <Users class="h-5 w-5 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ totalClientes }}</div>
                        <p class="text-xs text-muted-foreground mt-1">
                            Clientes registrados en el sistema
                        </p>
                    </CardContent>
                </Card>

                <!-- Total de Motores -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">
                            Total de Motores
                        </CardTitle>
                        <Car class="h-5 w-5 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ totalMotores }}</div>
                        <p class="text-xs text-muted-foreground mt-1">
                            Motores registrados en el sistema
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Últimos Registros -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Últimos Motores Registrados -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center gap-2">
                            <Clock class="h-5 w-5 text-primary" />
                            <CardTitle>Últimos Motores Registrados</CardTitle>
                        </div>
                        <CardDescription>
                            Últimos 10 motores agregados al sistema
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 px-2 text-xs font-medium text-muted-foreground">
                                            N° Serie
                                        </th>
                                        <th class="text-left py-2 px-2 text-xs font-medium text-muted-foreground">
                                            Marca
                                        </th>
                                        <th class="text-left py-2 px-2 text-xs font-medium text-muted-foreground">
                                            Modelo
                                        </th>
                                        <th class="text-left py-2 px-2 text-xs font-medium text-muted-foreground">
                                            Año
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr 
                                        v-for="(motor, index) in motoresRecientes" 
                                        :key="index"
                                        class="border-b last:border-0 hover:bg-muted/50 transition-colors"
                                    >
                                        <td class="py-2 px-2 text-sm font-medium">
                                            {{ motor.numero_serie }}
                                        </td>
                                        <td class="py-2 px-2 text-sm">
                                            {{ motor.marca }}
                                        </td>
                                        <td class="py-2 px-2 text-sm">
                                            {{ motor.modelo }}
                                        </td>
                                        <td class="py-2 px-2 text-sm">
                                            {{ motor.anio }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-if="motoresRecientes.length === 0" class="text-center text-sm text-muted-foreground py-8">
                                No hay motores registrados
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Últimos Clientes Registrados -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center gap-2">
                            <Clock class="h-5 w-5 text-green-600" />
                            <CardTitle>Últimos Clientes Registrados</CardTitle>
                        </div>
                        <CardDescription>
                            Últimos 10 clientes agregados al sistema
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 px-2 text-xs font-medium text-muted-foreground">
                                            Nombre
                                        </th>
                                        <th class="text-left py-2 px-2 text-xs font-medium text-muted-foreground">
                                            Teléfono
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr 
                                        v-for="(cliente, index) in clientesRecientes" 
                                        :key="index"
                                        class="border-b last:border-0 hover:bg-muted/50 transition-colors"
                                    >
                                        <td class="py-2 px-2 text-sm font-medium">
                                            {{ cliente.nombre }}
                                        </td>
                                        <td class="py-2 px-2 text-sm">
                                            {{ cliente.telefono }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-if="clientesRecientes.length === 0" class="text-center text-sm text-muted-foreground py-8">
                                No hay clientes registrados
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
