<template>
    <div :class="wrapperClass">
        <label :for="id" class="block text-sm font-medium">{{ label }}</label>
        <input :type="type" :id="id" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
            class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500" />
    </div>
</template>

<script setup>
const props = defineProps({
    label: String,
    id: String,
    type: {
        type: String,
        default: "text"
    },
    modelValue: String,
    wrapperClass: {
        type: String,
        default: "mt-4"
    }
});
</script>
