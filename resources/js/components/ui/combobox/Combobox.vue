<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { Check, ChevronsUpDown, Search } from "lucide-vue-next";

const props = defineProps<{
    items: Array<{ id: number | string; label: string }>;
    modelValue: number | string | null;
    placeholder?: string;
}>()

const emit = defineEmits(["update:modelValue"]);

const open = ref(false);
const search = ref("");

const filteredItems = computed(() => {
    return props.items.filter(i =>
        i.label.toLowerCase().includes(search.value.toLowerCase())
    );
});

const selectItem = (id: number | string) => {
    emit("update:modelValue", id);
    open.value = false;
};
</script>

<template>
    <div class="relative w-full">
        
        <!-- INPUT PRINCIPAL -->
        <button
            type="button"
            class="w-full rounded-md border border-input bg-background text-foreground px-3 py-2 flex justify-between items-center outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
            @click="open = !open"
        >
            <span class="text-left">
                {{ props.items.find(i => i.id === props.modelValue)?.label || props.placeholder || "Seleccionar..." }}
            </span>

            <ChevronsUpDown class="h-4 w-4 opacity-50" />
        </button>

        <!-- LISTA -->
        <div
            v-if="open"
            class="absolute mt-1 w-full rounded-md border border-input bg-background text-foreground shadow-md z-50 max-h-48 overflow-auto"
        >
            <!-- BUSCADOR -->
            <div class="flex items-center gap-2 p-2 border-b border-input bg-muted">
                <Search class="h-4 w-4 opacity-60" />
                <input
                    type="text"
                    v-model="search"
                    class="w-full outline-none text-sm bg-transparent text-foreground placeholder:text-muted-foreground"
                    placeholder="Buscar..."
                />
            </div>

            <!-- ITEMS -->
            <div 
                v-for="item in filteredItems" 
                :key="item.id"
                class="px-3 py-2 flex items-center justify-between cursor-pointer hover:bg-accent hover:text-accent-foreground"
                @click="selectItem(item.id)"
            >
                <span>{{ item.label }}</span>

                <Check 
                    v-if="item.id === props.modelValue" 
                    class="h-4 w-4 text-green-600" 
                />
            </div>

            <div v-if="filteredItems.length === 0" class="p-3 text-sm text-muted-foreground">
                No hay resultados.
            </div>
        </div>
    </div>
</template>
