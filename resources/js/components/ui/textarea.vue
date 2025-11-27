<script setup>
defineProps(['modelValue']);
defineEmits(['update:modelValue']);
</script>

<template>
  <textarea
    class="flex w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm
           placeholder:text-muted-foreground focus-visible:outline-none 
           focus-visible:ring-1 focus-visible:ring-ring 
           disabled:cursor-not-allowed disabled:opacity-50"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    v-bind="$attrs"
  ></textarea>
</template>
