<template>
  <div>
    <!-- component -->
    <div class="w-full mt-5">
      <div
        class="relative flex flex-col w-full h-full text-slate-700 bg-white shadow-md rounded-xl bg-clip-border"
      >
        <!-- header -->
        <div class="relative mx-4 mt-4 overflow-hidden text-slate-700 bg-white rounded-none bg-clip-border">
          <div class="flex items-center justify-between ">
            <div>
              <h3 class="text-lg font-semibold text-slate-800">Pedidos</h3>
              <p class="text-slate-500">Relacionados ao cliente</p>
            </div>

            <StatusFilter
              :statuses="statuses" :selected-status="selectedStatus"
              @status-change="setStatusFilter"
            />
          </div>
        </div>

        <div v-if="filteredOrders.length > 0">
          <table class="w-full mt-4 text-left table-auto min-w-max">
            <thead>
              <tr>
                <th
                  class="table-header-cell"
                >
                  <p
                    class="table-header-content"
                  >
                    ID
                  </p>
                </th>
                <th
                  class="table-header-cell"
                >
                  <p
                    class="table-header-content"
                  >
                    Data do Pedido
                  </p>
                </th>
                <th
                  class="table-header-cell"
                >
                  <p
                    class="table-header-content"
                  >
                    Status
                  </p>
                </th>
                <th
                  class="table-header-cell"
                >
                  <p
                    class="table-header-content"
                  >
                    Valor total
                  </p>
                </th>
                <th
                  class="table-header-cell"
                >
                  <p
                    class="table-header-content"
                  >
                    Valor pago
                  </p>
                </th>

                <th
                  class="table-header-cell"
                >
                  <p
                    class="table-header-content"
                  >
                    Status do Pagamento
                  </p>
                </th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="order in filteredOrders" :key="order.id" class="hover:bg-gray-100 cursor-pointer"
                @click="handleClick(order)"
              >
                <td class="p-4 border-b border-slate-200">
                  <div class="flex items-center gap-3">
                    <div class="flex flex-col">
                      <p class="text-sm text-slate-500">
                        {{ order.id }}
                      </p>
                    </div>
                  </div>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <div class="flex flex-col">
                    <p class="text-sm text-slate-500">
                      {{ formatDate(order.created_at) }}
                    </p>
                  </div>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <StatusIndicator :status="order.status" />
                </td>
                <td class="p-4 border-b border-slate-200">
                  <p class="text-sm text-slate-500">
                    {{ order.total_amount }}
                  </p>
                </td>

                <td class="p-4 border-b border-slate-200">
                  <p class="text-sm text-slate-500">
                    {{ order.amount_paid }}
                  </p>
                </td>

                <td class="p-4 border-b border-slate-200">
                  <StatusIndicator :status="paymentStatus(order)" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="p-4 text-slate-500 flex justify-center">
          Sem pedidos
        </div>

        <Pagination v-if="orders.length > 10" :links="links" class="mt-3 p-2" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { useDateUtils } from '@/Composables/useUtils'
import { router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import StatusIndicator from '../utils/StatusIndicator.vue'
import Pagination from '@/Shared/Paginator.vue'
import StatusFilter from '../filters/StatusFilter.vue'

let props = defineProps({
    orders: Array,
    links: Array,
    filterStatus: String,
})

const { formatDate } = useDateUtils()

const handleClick = (order) => {
    router.get(`/orders/${order.id}`)
}

const paymentStatus = (order) => {
    if (order.total_amount === order.amount_paid) {
        return ('Pago')
    }
    return ('Não Pago')
}

const statuses = [
    { value: '', label: 'Todos' },
    { value: 'Em andamento', label: 'Em andamento' },
    { value: 'Concluído', label: 'Concluído' },
]

const statusMapping = {
    'em-andamento': ['Entrega Não Agendada', 'Entrega Agendada'],
}

const selectedStatus = ref(props.filterStatus || '')

const filteredOrders = computed(() => {
    if (selectedStatus.value === '') {
        return props.orders
    }

    if (selectedStatus.value === 'em-andamento') {
        return props.orders.filter(order =>
            statusMapping['em-andamento'].includes(order.status),
        )
    }

    return props.orders.filter(order => order.status === selectedStatus.value)
})

const setStatusFilter = (status) => {
    selectedStatus.value = status
}

</script>
