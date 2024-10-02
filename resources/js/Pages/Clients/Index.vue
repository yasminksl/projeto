<template>

    <Head title="Clientes" />
    <Layout title="Clientes">

        <!-- Adicionar -->
        <template #headerContent>
            <div class="flex items-center ml-3">
                <AddButton @click="goToNewClient" value="Novo Cliente" />
            </div>
        </template>

        <!-- Pesquisar e Filtros -->
        <div class="flex mb-6 space-x-2">
            <SearchInput v-model="search" />
            <FilterButton @click="openClientFilterModal" />
        </div>

        <div v-if="clients.data.length === 0" class="text-center py-6">
            <p class="text-gray-500">Nenhum cliente encontrado</p>
        </div>

        <!-- Listagem de Clientes -->
        <ul v-else role="list" class="divide-y divide-gray-200 rounded">
            <li class="flex justify-between gap-x-6 py-5 bg-white px-4 rounded hover:bg-gray-200 cursor-pointer"
                v-for="client in clients.data" :key="client.id" @click="goToClient(client.id)">

                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex items-center">
                        <Link :href="`/clients/${client.id}`"
                            class="text-sm font-bold leading-6 text-gray-900 hover:underline">
                        {{ client.name }}
                        </Link>
                    </div>
                </div>

            </li>
        </ul>

        <!-- Paginator -->
        <Pagination v-if="clients.data.length > 10" :links="clients.meta.links" class="mt-6" />

        <ClientFilterModal :isVisible="isModalClientFilterVisible"
            @update:isVisible="isModalClientFilterVisible = $event" :filters="filters"
            @apply-filters="applyFiltersFromModal" />

    </Layout>

</template>

<script setup>
import { useSearch } from '@/Composables/useSearch';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useFilters } from '@/Composables/useFilters';
import Pagination from '@/Shared/Pagination.vue';
import Layout from '@/Shared/Layout.vue';
import SearchInput from '@/Components/inputs/SearchInput.vue';
import AddButton from '@/Components/actions/AddButton.vue';
import FilterButton from '@/Components/actions/FilterButton.vue';
import ClientFilterModal from '@/Components/modals/ClientFilterModal.vue';

let props = defineProps({
    clients: Object,
    filters: Object
});

const goToNewClient = () => {
    router.get('/clients/create');
};

const goToClient = (id) => {
    router.get(`/clients/${id}`);
};

const isModalClientFilterVisible = ref(false);

const openClientFilterModal = () => {
    isModalClientFilterVisible.value = true;
};

const { searchFilters, applyFilters } = useFilters(props.filters, '/clients');
const { search } = useSearch(props.filters, '/clients', null, searchFilters);

const applyFiltersFromModal = (modalFilters) => {
    const combinedFilters = Object.fromEntries(
        Object.entries({
            ...searchFilters.value,
            ...modalFilters,
        }).filter(([key, value]) => value !== null && value !== '')
    );

    searchFilters.value = combinedFilters;
    applyFilters();
};
</script>
