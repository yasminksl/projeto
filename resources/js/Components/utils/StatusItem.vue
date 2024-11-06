<template>
  <div class="flex justify-between items-start w-full">
    <div class="flex justify-center items-center space-x-4">
      <div class="flex flex-col justify-start items-center">
        <p class="text-lg leading-6 font-semibold text-gray-800">
          {{ label }}<br /><span class="font-normal">{{ value }}</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>

defineProps({
    label: String,
    value: String,
})

</script>
