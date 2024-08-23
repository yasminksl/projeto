<template>
    <div :class="wrapperClass">
        <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">{{ label }}</label>
        <input :id="id" :type="type" :placeholder="placeholder" :step="step" :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />
    </div>
</template>

<script setup>
let props = defineProps({
    wrapperClass: {
        type: String,
        default: 'mb-4'
    },
    id: String,
    type: {
        type: String,
        default: 'text'
    },
    placeholder: String,
    label: String,
    step: {
        type: String,
        default: ''
    },
    modelValue: [String, Number]
});
</script>
