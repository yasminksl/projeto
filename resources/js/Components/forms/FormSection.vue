<template>
    <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
        <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <slot />
        </div>
    </div>
</template>

<script setup>

let props = defineProps({
    title: String,
})

</script>
