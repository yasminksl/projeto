<template>
    <thead class="bg-gray-800">
        <tr>
            <th :class="class">ID</th>
            <th :class="class">Nome do
                Cliente</th>
            <th :class="class">Data de
                Criação</th>
            <th :class="class"
                v-if="showScheduledDateColumn">Data de
                Agendamento</th>
            <th :class="class"
                v-if="showDeliveryDateColumn">Data de
                Entrega</th>
            <th :class="class">Status da
                Entrega</th>
        </tr>
    </thead>
</template>

<script setup>

let props = defineProps({
    showScheduledDateColumn: Boolean,
    showDeliveryDateColumn: Boolean,
    class: {
        type: String,
        default: 'px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider'
    }
});

</script>
