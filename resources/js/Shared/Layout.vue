<template>
    <Nav />
    <div id="main-content" class="w-full max-w-[78.9rem] bg-gray-100 relative overflow-y-auto lg:ml-64 pt-16">
        <main>
            <div class="pt-6 px-6 sm:flex sm:items-start" :class="class">
                <div class="flex items-center space-x-3">
                    <Link v-if="active" :href="href">
                        <i class="fa-solid fa-arrow-left-long cursor-pointer"/>
                    </Link>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-4 lg:mb-0">
                        {{ title }}
                    </h1>
                </div>
                <slot name="headerContent" />
            </div>
            <div :class="divClass">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import Nav from './Nav.vue';
import { Link } from '@inertiajs/vue3';

let props = defineProps({
    title: String,
    class: {
        type: String,
        default: 'justify-between',
    },
    active: {
        type: Boolean,
        default: false,
    },
    href: String,
    divClass: {
        type: String,
        default: 'pt-6 px-6',
    },
});
</script>
