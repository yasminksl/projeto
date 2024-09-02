<template>

    <Head title="Pedidos" />
    <Layout title="Pedidos">
        <div class="min-h-screen bg-gray-100 p-4 sm:p-6">
            <div class="p-6 bg-white shadow-md rounded-lg mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                    <StatusFilter :statuses="statuses" :selectedStatus="selectedStatus"
                        @status-change="setStatusFilter" />
                    <SearchInput placeholder="Pesquisar pedidos..." v-model="search.search.value" />
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
                </div>

                <OrderTable :orders="orders" @order-click="goToOrder" />
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import SearchInput from '@/Components/inputs/SearchInput.vue';
import StatusFilter from '@/Components/filters/StatusFilter.vue';
import OrderTable from '@/Components/tables/OrderTable.vue';
import { ref } from 'vue';
import { useSearch } from '@/Composables/useSearch';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    orders: Object,
    filters: Object
});

const statuses = [
    { value: '', label: 'Ver todos' },
    { value: 'Entrega Não Agendada', label: 'Entrega Não Agendada' },
    { value: 'Entrega Agendada', label: 'Entrega Agendada' },
    { value: 'Concluído', label: 'Concluído' }
];

const selectedStatus = ref(props.filters.status || '');
const search = useSearch(props.filters, '/orders', selectedStatus);

const setStatusFilter = (status) => {
    selectedStatus.value = status;
};

const goToOrder = (id) => {
    router.get(`/orders/${id}`);
};
</script>
