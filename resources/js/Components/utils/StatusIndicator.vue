<template>
    <div class="w-max">
        <div :class="statusClasses">
            <span class="">{{ status }}</span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

let props = defineProps({
    status: String
});

const statusClasses = computed(() => ({
    'relative grid items-center px-2 py-1 font-sans text-xs font-bold uppercase rounded-md select-none whitespace-nowrap': true,
    'text-red-900 bg-red-500/20': props.status === 'Entrega Não Agendada' || props.status === 'Não Pago',
    'text-yellow-900 bg-yellow-500/20': props.status === 'Entrega Agendada',
    'text-green-900 bg-green-500/20': props.status === 'Concluído' || props.status === 'Pago',
}));
</script>
