<template>
    <div class="mb-5">
        <h2 class="text-lg font-bold leading-7 text-gray-900">{{ title }}</h2>
        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <slot />
        </div>
    </div>
</template>

<script setup>

let props = defineProps({
    title: String,
})

</script>
