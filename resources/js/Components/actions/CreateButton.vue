<template>
    <Link :href="href"
        class="rounded-md bg-indigo-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
    {{ text }}
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

let props = defineProps({
    href: String,
    text: String
});

</script>
