<template>

    <div class="min-h-full">
        <Nav />
    </div>

    <div>
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex" :class="class">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    {{ title }}
                </h1>
                <slot name="headerContent" />
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
    </div>

</template>

<script setup>
import Nav from './Nav.vue';

let props = defineProps({
    title: String,
    class: {
        type: String,
        default: 'justify-between'
    },
});

</script>
