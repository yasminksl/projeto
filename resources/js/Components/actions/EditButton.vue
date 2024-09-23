<template>
    <button class="flex items-center p-1.5 rounded-xl hover:rounded-3xl transition-all duration-300 text-white"
        :class="background">
        <i class="fa-solid fa-pen-to-square"></i>
        <p v-if="active" class="px-1">Editar</p>
    </button>
</template>

<script setup>

let props = defineProps({
    background: {
        type: String,
        default: 'bg-gray-400 hover:bg-black'
    },
    active: {
        type: Boolean,
        default: false,
    }
});

</script>
