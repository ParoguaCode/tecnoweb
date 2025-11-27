<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
defineProps({
    paginator: {
        type: Object,
        required: true,
    },
});

const makeLabel = (label: string) => {
    if (label.includes('Previous')) {
        return '<<';
    } else if (label.includes('Next')) {
        return '>>';
    } else {
        return label;
    }
};
</script>

<template>
    <div class="flex flex-col gap-4 items-start justify-between sm:flex-row">

        <div class="flex flex-wrap items-center overflow-hidden rounded-md shadow-lg">
            <div v-for="link in paginator.links" :key="link.url + Math.random().toString(36).substr(2, 9)">
                <Link
                    :href="link.url !== null ? link.url : '#'"
                    class="rounded-lg divide-gray-200 dark:divide-neutral-700 px-4 py-2"
                    :class="{
                        'hover:bg-gray-100 dark:hover:bg-neutral-700': link.url,
                        'dark:text-slate-500 text-slate-300': !link.url,
                        'bg-indigo-500 text-white': link.active,
                    }"
                >
                    <span v-html="makeLabel(link.label)"></span>
                </Link>
            </div>
        </div>

        <p class="text-sm text-slate-600">Mostrando {{ paginator.from }} a {{ paginator.to }} de {{ paginator.total }} resultados</p>
    </div>
</template>
