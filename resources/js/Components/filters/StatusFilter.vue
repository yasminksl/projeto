<template>
    <div class="flex items-center space-x-2 mr-3">
        <div v-for="status in statuses" :key="status.value" @click="setStatusFilter(status.value)"
            :class="['py-2 px-5 rounded-full', buttonClasses(status.value), 'bg-gray-100 text-black hover:bg-gray-200']"
            class="cursor-pointer">
            <p>{{ status.label }}</p>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    statuses: Array,
    selectedStatus: String
});

const emit = defineEmits(['status-change']);

const buttonClasses = (status) => ({
    'bg-gray-300': props.selectedStatus === status
});

const setStatusFilter = (status) => {
    emit('status-change', status);
};

</script>
