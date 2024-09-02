<template>
    <div
        class="inline-flex overflow-hidden bg-white border divide-x rounded-lg mr-3">
        <button v-for="status in statuses" :key="status.value" @click="setStatusFilter(status.value)"
            :class="buttonClasses(status.value)"
            class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm hover:bg-gray-100">
            {{ status.label }}
        </button>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    statuses: Array,
    selectedStatus: String
});

const emit = defineEmits(['status-change']);

const buttonClasses = (status) => ({
    'bg-gray-100': props.selectedStatus === status
});

const setStatusFilter = (status) => {
    emit('status-change', status);
};
</script>
