<template>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do
                        Cliente</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de
                        Criação</th>
                    <th v-if="showScheduledDateColumn"
                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de
                        Agendamento</th>
                    <th v-if="showDeliveryDateColumn"
                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de
                        Entrega</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status da
                        Entrega</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50 cursor-pointer"
                    @click="$emit('order-click', order.id)">
                    <td class="px-4 py-2 text-sm text-gray-900">{{ order.id }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ order.client_name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ formatDate(order.created_at) }}</td>
                    <td v-if="showScheduledDateColumn" class="px-4 py-2 text-sm text-gray-500">
                        {{ order.scheduled_delivery_date ? formatDate(order.scheduled_delivery_date) : 'N/A' }}</td>
                    <td v-if="showDeliveryDateColumn" class="px-4 py-2 text-sm text-gray-500">
                        {{ order.actual_delivery_date ? formatDate(order.actual_delivery_date) : 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500 flex items-center gap-x-1.5">
                        <div :class="statusClasses(order.status)">
                            <div :class="statusIndicatorClasses(order.status)"></div>
                        </div>
                        {{ order.status }}
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination :links="orders.meta.links" class="mt-6" />
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';
import { useDateUtils, useStatusUtils } from '@/Composables/useUtils.js';
import Pagination from '@/Shared/Pagination.vue';

const props = defineProps({
    orders: Object
});

const emit = defineEmits(['order-click']);

const { formatDate } = useDateUtils();
const { statusClasses, statusIndicatorClasses } = useStatusUtils();

const showScheduledDateColumn = computed(() => props.orders.data.some(order => order.scheduled_delivery_date));
const showDeliveryDateColumn = computed(() => props.orders.data.some(order => order.actual_delivery_date));
</script>
