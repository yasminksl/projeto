<template>
    <Link
        href="/"
        class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white"
        :class="{'bg-gray-900 text-white' : active}"
    >
        <slot />
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

let props = defineProps({
    active: Boolean
})
</script>
