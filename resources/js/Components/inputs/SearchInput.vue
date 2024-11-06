<template>
  <input
    :value="modelValue"
    type="text"
    :placeholder="placeholder"
    class="flex-grow border border-gray-200 rounded-lg px-2 focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<script setup>

defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Pesquisar...',
  },
})

defineEmits(['update:modelValue'])

</script>
