<template>

    <Head title="Pedidos" />
    <Layout title="Pedidos">

        <div class="min-h-screen bg-gray-100 p-4 sm:p-6">
            <div class="p-6 bg-white shadow-md rounded-lg mb-6">

                <!-- Botões e Espaço de Pesquisa -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                    <!-- Espaço para pesquisa e filtros -->
                    <SearchInput placeholder="Pesquisar pedidos..." v-model="search" />

                    <!-- Botão para cadastrar novo pedido e ver pedidos concluídos -->
                    <div class="flex items-center ml-6">
                        <Link href="/orders/create" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Cadastrar Novo Pedido
                        </Link>
                        <Link href="#" class="px-4 py-2 ml-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Ver pedidos concluídos
                        </Link>
                    </div>
                </div>

                <!-- Tabela de pedidos -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nome do Cliente
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data de Criação
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status da Entrega
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50 cursor-pointer"
                                @click="goToOrder(order.id)">
                                <td class="px-4 py-2 text-sm text-gray-900">
                                    {{ order.id }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-500">
                                    {{ order.client_name }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-500">
                                    {{ new Date(order.created_at).toLocaleDateString() }}
                                </td>

                                <td class="px-4 py-2 text-sm text-gray-500 flex items-center gap-x-1.5">
                                    <div :class="{
                                        'flex-none rounded-full p-1': true,
                                        'bg-red-500/20': order.status === 'Entrega Não Agendada',
                                        'bg-yellow-500/20': order.status === 'Entrega Agendada',
                                        // 'bg-green-500/20': order.status === 'Concluída'
                                    }">
                                        <div :class="{
                                            'h-1.5 w-1.5 rounded-full': true,
                                            'bg-red-500': order.status === 'Entrega Não Agendada',
                                            'bg-yellow-500': order.status === 'Entrega Agendada',
                                            // 'bg-green-500': order.status === 'Concluída'
                                        }"></div>
                                    </div>
                                    {{ order.status }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :links="orders.meta.links" class="mt-6" />
                </div>

            </div>

        </div>
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import SearchInput from '@/Components/inputs/SearchInput.vue';
import { useSearch } from '@/Composables/useSearch';
import { Link } from '@inertiajs/vue3';
import Pagination from '@/Shared/Pagination.vue';
import { router } from '@inertiajs/vue3';

let props = defineProps({
    orders: Object,
    filters: Object
});

let { search } = useSearch(props.filters, '/orders');

const goToOrder = (id) => {
    router.get(`/orders/${id}`);
};

</script>
