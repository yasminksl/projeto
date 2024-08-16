<template>
  <input
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    type="text"
    placeholder="Pesquisar..."
    class="flex-grow border border-gray-200 rounded-lg px-2"
  />
</template>

<script setup>

let props = defineProps({
  modelValue: String,
});
</script>
