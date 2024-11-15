<template>
  <Head title="Produtos" />
  <Layout title="Produtos">
    <template #headerContent>
      <div class="flex items-center ml-3">
        <AddButton value="Novo Produto" @click="goToNewProduct" />
      </div>
    </template>

    <div class="flex mb-6 space-x-2">
      <SearchInput v-model="search" />
      <FilterButton @click="openProductFilterModal" />
    </div>

    <div v-if="products.length === 0" class="text-center py-6">
      <p class="text-gray-500">Nenhum produto encontrado</p>
    </div>

    <div v-else class="overflow-hidden rounded-lg shadow-md mb-10">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-800 text-white">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
              ID
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
              Nome
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
              Preço
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="product in products" :key="product.id" class="hover:bg-gray-50 cursor-pointer"
            @click="goToProduct(product.id)"
          >
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
    </div>

    <ProductFilterModal
      :is-visible="isModalProductFilterVisible"
      :filters="filters" @update:is-visible="isModalProductFilterVisible = $event"
      @apply-filters="applyFiltersFromModal"
    />
  </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue'
import { router } from '@inertiajs/vue3'
import { useSearch } from '@/Composables/useSearch'
import { ref } from 'vue'
import { useFilters } from '@/Composables/useFilters'
import SearchInput from '@/Components/inputs/SearchInput.vue'
import AddButton from '@/Components/actions/AddButton.vue'
import FilterButton from '@/Components/actions/FilterButton.vue'
import ProductFilterModal from '@/Pages/Products/Index/Components/ProductFilterModal.vue'

let props = defineProps({
    products: Object,
    filters: Object,
})

const goToProduct = (id) => {
    router.get(`/products/${id}`)
}

const goToNewProduct = () => {
    router.get('/products/create')
}

const isModalProductFilterVisible = ref(false)

const openProductFilterModal = () => {
    isModalProductFilterVisible.value = true
}

const { searchFilters, applyFilters } = useFilters(props.filters, '/products')
const { search } = useSearch(props.filters, '/products', null, searchFilters)

const applyFiltersFromModal = (modalFilters) => {
    const combinedFilters = Object.fromEntries(
        Object.entries({
            ...searchFilters.value,
            ...modalFilters,
        }).filter(([value]) => value !== null && value !== ''),
    )

    searchFilters.value = combinedFilters
    applyFilters()
}

</script>
