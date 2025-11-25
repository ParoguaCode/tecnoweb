<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { ref, watchEffect } from 'vue';

//NOTA: esta alerta se ocupa muchas veces, sin embargo, lo de watchefect y showAlert solo sirve para PAGAR y actualizar permisos

const Props = defineProps<{
    success?: string | null;
    error?: string | null;
}>();

const showAlert = ref(false);

watchEffect(() => {
    if (Props.success || Props.error) {
        showAlert.value = true;
        setTimeout(() => {
            showAlert.value = false;
        }, 3000);
    }
});

</script>

<template>
    <Alert v-if="showAlert" class="bg-stone-950 text-white dark:bg-white dark:text-black">
        <AlertTitle>Notificación</AlertTitle>
        <AlertDescription>
            <div v-if="Props.success" class="relative rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700" role="alert">
                <span class="block sm:inline">{{ Props.success }}</span>
            </div>
            <div v-if="Props.error" class="relative rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700" role="alert">
                <strong class="font-bold">Error! </strong>
                <span class="block sm:inline">{{ Props.error }}</span>
            </div>
        </AlertDescription>
    </Alert>
</template>
