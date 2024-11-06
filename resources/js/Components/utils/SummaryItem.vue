<template>
  <div class="flex justify-between w-full">
    <p class="text-base leading-4 text-gray-800" :class="aditionalClass">{{ label }}</p>
    <p class="text-base leading-4 text-gray-600" :class="aditionalClass">{{ value }}</p>
  </div>
</template>

<script setup>

defineProps({
    label: String,
    value: String,
    aditionalClass: String,
})

</script>
