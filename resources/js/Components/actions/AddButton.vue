<template>
  <button
    class="px-3 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded flex items-center space-x-2"
    :class="aditionalClass"
    type="button" @click="handleClick"
  >
    <i class="fa-solid fa-plus" />
    <div>{{ value }}</div>
  </button>
</template>

<script setup>

defineProps({
    value: String,
    aditionalClass: String,
})

const emit = defineEmits(['click'])

const handleClick = () => {
    emit('click')
}

</script>
