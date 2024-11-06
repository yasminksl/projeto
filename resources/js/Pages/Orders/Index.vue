<template>
  <Head title="Pedidos" />
  <Layout title="Pedidos">
    <template #headerContent>
      <div class="flex items-center ml-3">
        <AddButton value="Novo Pedido" @click="goToNewOrder" />
      </div>
    </template>

    <div class="min-h-screen bg-gray-100">
      <div class="p-6 bg-white shadow-md rounded-lg mb-6">
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 justify-between items-center mb-4">
          <StatusFilter
            :statuses="statuses" :selected-status="selectedStatus"
            @status-change="setStatusFilter"
          />
          <FilterButton @click="openOrderFilterModal" />
        </div>
        <div v-if="orders.data.length === 0" class="text-center py-6">
          <p class="text-gray-500">Nenhum pedido encontrado</p>
        </div>
        <OrderTable v-else :orders="orders" @order-click="goToOrder" />
      </div>
    </div>

    <OrderFilterModal
      :is-visible="isModalOrderFilterVisible" :clients="clients.data"
      :filters="filters" @update:is-visible="isModalOrderFilterVisible = $event" @apply-filters="applyFiltersFromModal"
    />
  </Layout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useFilters } from '@/Composables/useFilters'
import Layout from '@/Shared/Layout.vue'
import StatusFilter from '@/Components/filters/StatusFilter.vue'
import OrderTable from '@/Components/tables/OrderTable.vue'
import FilterButton from '@/Components/actions/FilterButton.vue'
import OrderFilterModal from '@/Components/modals/OrderFilterModal.vue'
import AddButton from '@/Components/actions/AddButton.vue'

const props = defineProps({
    orders: Object,
    filters: Object,
    clients: Object,
})

const statuses = [
    { value: '', label: 'Ver todos' },
    { value: 'Entrega Não Agendada', label: 'Entrega Não Agendada' },
    { value: 'Entrega Agendada', label: 'Entrega Agendada' },
    { value: 'Concluído', label: 'Concluído' },
]

const selectedStatus = ref(props.filters.status || '')

const setStatusFilter = (status) => {
    selectedStatus.value = status
    applyCombinedFilters()
}

const goToOrder = (id) => {
    router.get(`/orders/${id}`)
}

const goToNewOrder = () => {
    router.get('/orders/create')
}

const isModalOrderFilterVisible = ref(false)

const openOrderFilterModal = () => {
    isModalOrderFilterVisible.value = true
}

const { searchFilters, applyFilters } = useFilters(props.filters, '/orders')

const applyCombinedFilters = () => {
    const combinedFilters = {
        ...searchFilters.value,
        status: selectedStatus.value,
    }

    searchFilters.value = combinedFilters
    applyFilters()
}

const applyFiltersFromModal = (modalFilters) => {
    const combinedFilters = Object.fromEntries(
        Object.entries({
            ...searchFilters.value,
            ...modalFilters,
            status: selectedStatus.value,
        }).filter(([value]) => value !== null && value !== ''),
    )

    searchFilters.value = combinedFilters
    applyFilters()
}
</script>
