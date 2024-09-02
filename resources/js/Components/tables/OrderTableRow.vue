<template>
    <tbody class="bg-white divide-y divide-gray-200">
        <tr class="hover:bg-gray-50 cursor-pointer" @click="handleClick">
            <td class="px-4 py-2 text-sm text-gray-900">{{ order.id }}</td>
            <td :class="class">{{ order.client_name }}</td>
            <td :class="class">{{ formatDate(order.created_at) }}</td>
            <td v-if="showScheduledDateColumn" :class="class">
                {{ order.scheduled_delivery_date ? formatDate(order.scheduled_delivery_date) : 'N/A' }}
            </td>
            <td v-if="showDeliveryDateColumn" :class="class">
                {{ order.actual_delivery_date ? formatDate(order.actual_delivery_date) : 'N/A' }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-500 flex items-center gap-x-1.5">
                <StatusIndicator :status="order.status"/>
                {{ order.status }}
            </td>
        </tr>
    </tbody>
</template>

<script setup>
import { useDateUtils } from '@/Composables/useUtils.js';
import StatusIndicator from '../utils/StatusIndicator.vue';

const props = defineProps({
    order: Object,
    class: {
        type: String,
        default: 'px-4 py-2 text-sm text-gray-500'
    },
    showScheduledDateColumn: Boolean,
    showDeliveryDateColumn: Boolean,
});

const emit = defineEmits(['order-click']);

const { formatDate } = useDateUtils();

const handleClick = () => {
    emit('order-click');
};
</script>
