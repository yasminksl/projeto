<template>
    <div :class="statusClasses">
        <div :class="indicatorClasses"></div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

let props = defineProps({
    status: String
});

const statusClasses = computed(() => ({
    'flex-none rounded-full p-1': true,
    'bg-red-500/20': props.status === 'Entrega Não Agendada',
    'bg-yellow-500/20': props.status === 'Entrega Agendada',
    'bg-green-500/20': props.status === 'Concluído'
}));

const indicatorClasses = computed(() => ({
    'h-1.5 w-1.5 rounded-full': true,
    'bg-red-500': props.status === 'Entrega Não Agendada',
    'bg-yellow-500': props.status === 'Entrega Agendada',
    'bg-green-500': props.status === 'Concluído'
}));
</script>
