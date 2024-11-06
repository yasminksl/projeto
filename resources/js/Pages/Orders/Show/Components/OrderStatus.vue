<template>
  <div class="flex flex-col justify-center px-4 py-6 md:p-6 xl:p-8 w-full bg-gray-50 space-y-6">
    <h3 class="text-xl font-semibold leading-5 text-gray-800">Dados da Entrega</h3>
    <StatusItem label="Status" :value="`${order.status}`" />
    <StatusItem v-if="order.scheduled_delivery_date" label="Entrega agendada para:" :value="`${formatDate(order.scheduled_delivery_date)}`" />
    <StatusItem v-if="order.actual_delivery_date" label="Entrega concluída em:" :value="`${formatDate(order.actual_delivery_date)}`" />

    <!-- Atualizar datas -->
    <ActionButton icon="fa-solid fa-pencil" value="Atualizar Datas" @click="openModalDate" />
  </div>

  <DateModal
    :initial-scheduled-date="order.scheduled_delivery_date" :initial-actual-date="order.actual_delivery_date"
    :is-visible="isModalDateVisible" :order-id="order.id" @update:is-visible="isModalDateVisible = $event"
    @update:dates="updateDates"
  />
</template>

<script setup>
import { ref } from 'vue'
import { useDateUtils } from '@/Composables/useUtils'
import DateModal from './DateModal.vue'
import StatusItem from '@/Components/utils/StatusItem.vue'
import ActionButton from '@/Components/actions/ActionButton.vue'

let props = defineProps({
    order: Object,
})

const { formatDate } = useDateUtils()

const isModalDateVisible = ref(false)

const openModalDate = () => {
    isModalDateVisible.value = true
}

const closeModal = () => {
    isModalDateVisible.value = false
}

const localOrderScheduledDeliveryDate = ref(props.order.scheduled_delivery_date)
const localOrderActualDeliveryDate = ref(props.order.actual_delivery_date)

const updateDates = (dates) => {
    localOrderScheduledDeliveryDate.value = dates.scheduledDeliveryDate
    localOrderActualDeliveryDate.value = dates.actualDeliveryDate

    closeModal()
}

</script>
