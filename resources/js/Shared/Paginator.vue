<template>
    <nav class="flex items-center justify-center">
        <div class="bg-gray-100 px-3 py-2 space-x-2 border border-gray-200 rounded text-gray-800">
            <Component v-if="showPrevious" :is="prevLink.url ? 'Link' : 'span'" :href="prevLink.url"
                v-html="prevLink.label"></Component>

            <Component v-for="link in links" :key="link.label" :is="link.url ? 'Link' : 'span'" :href="link.url"
                class="px-3 py-1 rounded" :class="{
                    'text-gray-300': !link.url,
                    'font-bold text-white': link.active,
                    'bg-gray-800': link.active
                }" v-html="link.label"></Component>

            <Component v-if="showNext" :is="nextLink.url ? 'Link' : 'span'" :href="nextLink.url"
                v-html="nextLink.label"></Component>
        </div>
    </nav>
</template>

<script setup>
import { ref, watchEffect } from 'vue';

let props = defineProps({
    links: Array
})

const paginationLinks = ref([...props.links]);

watchEffect(() => {
    if (paginationLinks.value.length > 0) {
        paginationLinks.value[0].label = '<i class="fa-solid fa-chevron-left"></i>';
        paginationLinks.value[paginationLinks.value.length - 1].label = '<i class="fa-solid fa-chevron-left fa-flip-horizontal"></i>';
    }
});

</script>
