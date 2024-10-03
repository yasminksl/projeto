<template>
    <nav class="flex items-center justify-center">
        <div :class="background" class="px-3 py-2 space-x-2 border border-gray-200 rounded text-gray-800">
            <Component v-for="link in formattedLinks" :key="link.label" :is="link.url ? 'Link' : 'span'"
                :href="link.url" class="px-3 py-1 rounded" :class="{
                    'text-gray-300': !link.url,
                    'font-bold text-white': link.active,
                    'bg-gray-800': link.active
                }" v-html="link.label"></Component>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue';

let props = defineProps({
    links: Array,
    background: {
        type: String,
        default: 'bg-gray-100',
    },
});

const formattedLinks = computed(() => {
    const newLinks = [...props.links];

    if (newLinks.length > 0) {
        newLinks[0].label = '<i class="fa-solid fa-chevron-left"></i>';
        newLinks[newLinks.length - 1].label = '<i class="fa-solid fa-chevron-right"></i>';
    }

    return newLinks;
});
</script>
