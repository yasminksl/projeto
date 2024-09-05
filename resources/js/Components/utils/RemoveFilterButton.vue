<template>
    <button
        v-if="filterValue"
        @click.prevent="removeFilter"
        class="bg-gray-100 px-3 py-2 rounded ml-2 flex items-center hover:bg-gray-200 transition-all mt-2"
    >
        <i class="fa-solid fa-xmark mr-2"></i>
        {{ label }}: {{ displayValue }}
    </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: String,
    filterValue: [String, Object],
    getDisplayValue: Function
});

const emit = defineEmits(['remove-filter']);

const removeFilter = () => {
    emit('remove-filter');
};

const displayValue = computed(() => {
    if (props.getDisplayValue) {
        return props.getDisplayValue(props.filterValue);
    }
    return props.filterValue;
});
</script>
