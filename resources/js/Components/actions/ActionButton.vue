<template>
  <div class="w-full flex justify-center items-center mt-6">
    <button
      class="hover:bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 py-5
            w-96 md:w-full bg-gray-800 text-base font-medium leading-4 text-white p-3"
      :class="aditionalClass"
      @click="handleClick"
    >
      {{ value }}
      <i class="ml-1" :class="icon" />
    </button>
  </div>
</template>

<script setup>

defineProps({
    value: String,
    aditionalClass: String,
    icon: String,
})

const emit = defineEmits(['click'])

const handleClick = () => {
    emit('click')
}

</script>
