<template>
    <div class="mb-4">
        <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">{{ label }}</label>
        <select :id="id" v-model="internalValue"
            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer"
            :required="required">
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.text }}
            </option>
        </select>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    modelValue: [String, Number],
    options: {
        type: Array,
        default: () => []
    },
    id: {
        type: String,
        default: 'status'
    },
    label: {
        type: String,
        default: 'Status'
    },
    required: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const internalValue = ref(props.modelValue);

watch(internalValue, (newValue) => {
    emit('update:modelValue', newValue);
});

// const computedOptions = computed(() => {
//     return props.options.map(option => ({
//         value: option.value,
//         text: option.text
//     }));
// });
</script>
