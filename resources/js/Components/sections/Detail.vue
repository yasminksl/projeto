<template>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="block text-sm font-medium text-gray-900 sm:mt-px">{{ label }}</dt>
        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ value }}</dd>
    </div>
</template>

<script setup>

let props = defineProps({
    label: String,
    value: String
});

</script>
