<template>
    <div class="flex flex-col justify-center px-4 py-6 md:p-6 xl:p-8 w-full bg-gray-50 space-y-6">
        <h3 class="text-xl font-semibold leading-5 text-gray-800">Dados da Entrega</h3>
        <StatusItem label="Status" :value="`${order.status}`"/>
        <StatusItem label="Entrega agendada para:" :value="`${formatDate(order.scheduled_delivery_date)}`" v-if="order.scheduled_delivery_date"/>
        <StatusItem label="Entrega concluída em:" :value="`${formatDate(order.actual_delivery_date)}`" v-if="order.actual_delivery_date"/>

        <!-- Atualizar datas -->
        <ActionButton icon="fa-solid fa-pencil" @click="openModalDate" value="Atualizar Datas"/>
    </div>

    <DateModal :initialScheduledDate="order.scheduled_delivery_date" :initialActualDate="order.actual_delivery_date"
        :isVisible="isModalDateVisible" :orderId="order.id" @update:isVisible="isModalDateVisible = $event"
        @update:dates="updateDates" />
</template>

<script setup>
import { ref } from 'vue';
import { useDateUtils } from '@/Composables/useUtils';
import DateModal from '../modals/DateModal.vue';
import StatusItem from '../utils/StatusItem.vue';
import ActionButton from '../actions/ActionButton.vue';

let props = defineProps({
    order: Object
});

const { formatDate } = useDateUtils();

const isModalDateVisible = ref(false);

const openModalDate = () => {
    isModalDateVisible.value = true;
};

const closeModal = () => {
    isModalDateVisible.value = false;
};

const updateDates = (dates) => {
    props.order.scheduled_delivery_date = dates.scheduledDeliveryDate;
    props.order.actual_delivery_date = dates.actualDeliveryDate;

    closeModal();
};

</script>
