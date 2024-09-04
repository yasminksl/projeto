<template>

    <Head title="Pedidos" />
    <Layout title="Pedidos">
        <template #headerContent>
            <div class="flex items-center ml-3">
                <Link href="/orders/create"
                    class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center space-x-1">
                <div>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                </div>
                <div>Adicionar Pedido</div>
                </Link>
            </div>
        </template>

        <div class="min-h-screen bg-gray-100 p-4 sm:p-6">
            <div class="p-6 bg-white shadow-md rounded-lg mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                    <StatusFilter :statuses="statuses" :selectedStatus="selectedStatus"
                        @status-change="setStatusFilter" />
                    <OrderFilter @click="openOrderFilterModal" />
                </div>
                <OrderTable :orders="orders" @order-click="goToOrder" />
            </div>
        </div>

        <OrderFilterModal :isVisible="isModalOrderFilterVisible" @update:isVisible="isModalOrderFilterVisible = $event"
            :clients="clients.data" :filters="filters" @apply-filters="applyFiltersFromModal" />
    </Layout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
import StatusFilter from '@/Components/filters/StatusFilter.vue';
import OrderTable from '@/Components/tables/OrderTable.vue';
import OrderFilter from '@/Components/filters/OrderFilter.vue';
import OrderFilterModal from '@/Components/modals/OrderFilterModal.vue';
import { useFilters } from '@/Composables/useFilters';

const props = defineProps({
    orders: Object,
    filters: Object,
    clients: Object
});

const statuses = [
    { value: '', label: 'Ver todos' },
    { value: 'Entrega Não Agendada', label: 'Entrega Não Agendada' },
    { value: 'Entrega Agendada', label: 'Entrega Agendada' },
    { value: 'Concluído', label: 'Concluído' }
];

const selectedStatus = ref(props.filters.status || '');

const setStatusFilter = (status) => {
    selectedStatus.value = status;
    applyCombinedFilters();
};

const goToOrder = (id) => {
    router.get(`/orders/${id}`);
};

const isModalOrderFilterVisible = ref(false);

const openOrderFilterModal = () => {
    isModalOrderFilterVisible.value = true;
};

const closeOrderFilterModal = () => {
    isModalOrderFilterVisible.value = false;
};

const { searchFilters, applyFilters } = useFilters(props.filters, '/orders');

const applyCombinedFilters = () => {
    const combinedFilters = {
        ...searchFilters.value,
        status: selectedStatus.value
    };

    searchFilters.value = combinedFilters;
    applyFilters();
};

const applyFiltersFromModal = (modalFilters) => {
    const combinedFilters = Object.fromEntries(
        Object.entries({
            ...searchFilters.value,
            ...modalFilters,
            status: selectedStatus.value
        }).filter(([key, value]) => value !== null && value !== '')
    );

    searchFilters.value = combinedFilters;
    applyFilters();
};
</script>
