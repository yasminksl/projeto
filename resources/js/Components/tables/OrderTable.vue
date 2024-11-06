<template>
  <div class="overflow-x-auto rounded-lg">
    <table class="min-w-full bg-white border border-gray-300 divide-y divide-white">
      <OrderTableHeader
        :show-scheduled-date-column="showScheduledDateColumn"
        :show-delivery-date-column="showDeliveryDateColumn"
      />
      <OrderTableRow
        v-for="order in formattedOrders"
        :key="order.id" :show-scheduled-date-column="showScheduledDateColumn" :show-delivery-date-column="showDeliveryDateColumn"
        :order="order" @order-click="$emit('order-click', order.id)"
      />
    </table>
    <Pagination :links="orders.meta.links" class="mt-6" />
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue'
import { useDateUtils } from '@/Composables/useUtils'
import Pagination from '@/Shared/Paginator.vue'
import OrderTableHeader from './OrderTableHeader.vue'
import OrderTableRow from './OrderTableRow.vue'

const props = defineProps({
    orders: Object,
})

defineEmits(['order-click'])

const { formatDate } = useDateUtils()

const showScheduledDateColumn = computed(() => props.orders.data.some(order => order.scheduled_delivery_date))
const showDeliveryDateColumn = computed(() => props.orders.data.some(order => order.actual_delivery_date))

const formattedOrders = computed(() => {
    return props.orders.data.map(order => ({
        ...order,
        formattedScheduledDate: order.scheduled_delivery_date ? formatDate(order.scheduled_delivery_date) : '',
        formattedDeliveryDate: order.actual_delivery_date ? formatDate(order.actual_delivery_date) : '',
    }))
})
</script>
