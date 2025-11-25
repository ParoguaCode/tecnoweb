<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Alerta from '@/components/global/Alerta.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});


const page = usePage<SharedData>();
const flash = ref({ ...page.props.flash });

watch(
    () => page.props.flash,
    (newFlash) => {
        flash.value = { ...newFlash };
        if (flash.value.success || flash.value.error) {
            setTimeout(() => {
                flash.value = { success: '', error: '' };
            }, 3000);
        }
    },
    { immediate: true, deep: true },
);

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Opciones de apariencia -->
        <div style="position: absolute; top: 0; right: 0; z-index: 1000">
            <AppearanceTabs />
        </div>
        <!-- Mensajes flash -->
        <div v-if="flash.success || flash.error" class="absolute left-1/2 top-10 z-50 m-4 -translate-x-1/2 transform">
            <Alerta :success="flash.success" :error="flash.error"></Alerta>
        </div>
        <slot />
    </AppLayout>
</template>
