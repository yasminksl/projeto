<template>
  <input
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    type="text"
    :placeholder="placeholder"
    class="flex-grow border border-gray-200 rounded-lg px-2 focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0"
  />
</template>

<script setup>

let props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Pesquisar...'
  },
});
</script>
