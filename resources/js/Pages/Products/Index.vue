<template>

    <Head title="Produtos" />
    <Layout title="Produtos">
        <template #headerContent>
            <div class="flex items-center ml-3">
                <AddButton @click="goToNewProduct" value="Novo Produto" />
            </div>
        </template>

        <div class="flex mb-6 space-x-2">
            <SearchInput v-model="search" />
            <FilterButton @click="openProductFilterModal" />
        </div>

        <div v-if="products.length === 0" class="text-center py-6">
            <p class="text-gray-500">Nenhum produto encontrado</p>
        </div>

        <table v-else class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-150">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Preço
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50 cursor-pointer"
                    @click="goToProduct(product.id)">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ product.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ product.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ product.price }}
                    </td>

                </tr>
            </tbody>
        </table>

        <ProductFilterModal :isVisible="isModalProductFilterVisible"
            @update:isVisible="isModalProductFilterVisible = $event" :filters="filters"
            @apply-filters="applyFiltersFromModal" />

    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { router } from '@inertiajs/vue3';
import { useSearch } from '@/Composables/useSearch';
import { ref } from 'vue';
import { useFilters } from '@/Composables/useFilters';
import SearchInput from '@/Components/inputs/SearchInput.vue';
import AddButton from '@/Components/actions/AddButton.vue';
import FilterButton from '@/Components/actions/FilterButton.vue';
import ProductFilterModal from '@/Components/modals/ProductFilterModal.vue';

let props = defineProps({
    products: Object,
    filters: Object
});

const goToProduct = (id) => {
    router.get(`/products/${id}`);
};

const goToNewProduct = (id) => {
    router.get(`/products/create`);
};

const isModalProductFilterVisible = ref(false);

const openProductFilterModal = () => {
    isModalProductFilterVisible.value = true;
};

const closeProductFilterModal = () => {
    isModalProductFilterVisible.value = false;
};

const { searchFilters, applyFilters } = useFilters(props.filters, '/products');
const { search } = useSearch(props.filters, '/products', null, searchFilters);

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
