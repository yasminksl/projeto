<template>
  <button
    class="flex items-center p-1.5 rounded-xl hover:rounded-3xl transition-all duration-300"
    :class="color"
  >
    <i class="fa-solid fa-pen-to-square" />
    <p v-if="active" class="px-1">Editar</p>
  </button>
</template>

<script setup>

defineProps({
    color: {
        type: String,
        default: 'text-gray-800 hover:text-gray-600',
    },
    active: {
        type: Boolean,
        default: false,
    },
})

</script>
