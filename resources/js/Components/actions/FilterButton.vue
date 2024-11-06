<template>
  <button
    class="bg-gray-800 hover:bg-gray-700 text-white px-3 py-2 rounded ml-2 flex items-center transition-all"
    @click="handleClick"
  >
    Filtros
    <i class="fa-solid fa-filter ml-2" />
  </button>
</template>

<script setup>

const emit = defineEmits(['click'])

const handleClick = () => {
    emit('click')
}

</script>
