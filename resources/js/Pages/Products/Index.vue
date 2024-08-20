<template>

    <Head title="Produtos" />
    <Layout title="Produtos">
        <div class="flex mb-6 space-x-2">
            <SearchInput v-model="search" />
        </div>

        <table class="min-w-full divide-y divide-gray-200">
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

    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { router } from '@inertiajs/vue3';
import SearchInput from '@/Components/SearchInput.vue';
import { useSearch } from '@/Composables/useSearch';

let props = defineProps({
    products: Object,
    filters: Object
});

let {search} = useSearch(props.filters, '/products');

const goToProduct = (id) => {
    router.get(`/products/${id}`);
};

</script>
