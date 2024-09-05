<template>
    <button class="bg-gray-100 px-3 py-2 rounded ml-2 flex items-center hover:bg-gray-200 transition-all"
        @click="handleClick">
        Filtros
        <i class="fa-solid fa-filter ml-2"></i>
    </button>
</template>

<script setup>

const emit = defineEmits(['click']);

const handleClick = () => {
    emit('click');
};

</script>
