<template>
    <button v-if="filterValue" @click.prevent="removeFilter"
        class="bg-gray-100 px-3 py-2 rounded-full shadow-sm flex items-center hover:bg-gray-200 transition-all mt-2">
        <div class="text-gray-400">
            {{ label }}:
        </div>
        <div class="ml-1">
            {{ displayValue }}
        </div>
        <i class="fa-regular fa-circle-xmark text-gray-400 ml-2"></i>
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
