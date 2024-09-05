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
    'bg-gray-300': props.selectedStatus === status && status === '',
    'text-red-900 bg-red-500/20 hover:bg-red-400/20': props.selectedStatus === status && status === 'Entrega Não Agendada',
    'text-yellow-900 bg-yellow-500/20 hover:bg-yellow-400/20': props.selectedStatus === status && (status === 'Entrega Agendada' || status === 'Em andamento'),
    'text-green-900 bg-green-500/20 hover:bg-green-400/20': props.selectedStatus === status && status === 'Concluído',
});

const setStatusFilter = (status) => {
    emit('status-change', status);
};
</script>
