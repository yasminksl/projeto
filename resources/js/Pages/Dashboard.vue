<template>
  <Head title="Dashboard" />
  <Layout div-class="px-6">
    <div class="w-full grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-4">
      <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8  2xl:col-span-2">
        <div class="flex items-center justify-between mb-4">
          <div class="flex-shrink-0">
            <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ saleCurrentMonth
            }}</span>
            <h3 class="text-base font-normal text-gray-500">Vendas neste mês</h3>
          </div>
          <div class="flex items-center justify-end flex-1 text-green-500 text-base font-bold">
            {{ salesPercentageIncrease }}%
            <i class="fa-solid fa-arrow-up ml-1" />
          </div>
        </div>
        <SalesChart :sales="sales" />
      </div>
      <div class="bg-white shadow rounded-lg mb-4 h-full flex-1">
        <div class="mt-2 p-4 sm:p-6 border-gray-200">
          <h3 class="text-xl font-bold leading-none text-gray-900 mb-2">
            Valor total a receber: {{
              formatCurrency(totalOutstandingBalance) }}
          </h3>
          <span class="text-base font-normal text-gray-500">{{ Object.keys(clients).length }}
            Clientes
            Inadimplentes</span>
        </div>
        <div class="flex-1 max-h-96 border-t border-gray-200 overflow-y-auto">
          <ul v-for="client in clients" :key="client.id" role="list" class="border-b border-gray-200">
            <Link as="button" :href="`/clients/${client.id}`">
              <li
                class="flex items-center h-full px-3 py-3 sm:py-4 hover:bg-gray-100 transition-colors duration-200"
              >
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ client.name }}
                  </p>
                </div>
                <div class="inline-flex items-center text-base font-semibold text-red-500">
                  {{ formatCurrency(client.outstanding_balance) }}
                </div>
              </li>
            </Link>
          </ul>
        </div>
      </div>
    </div>
    <div class="mt-4 w-full grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
      <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{
              profitCurrentMonth }}</span>
            <h3 class="text-base font-normal text-gray-500">Valor recebido neste mês</h3>
          </div>
          <div class="ml-5 w-0 flex items-center justify-end flex-1 text-green-500 text-base font-bold">
            {{ profitPercentageIncrease }}%
            <i class="fa-solid fa-arrow-up ml-1" />
          </div>
        </div>
      </div>
      <Link as="button" href="/orders?status=Concluído">
        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 hover:bg-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{
                ordersByStatus['Concluído'] }}</span>
              <h3 class="text-base font-normal text-gray-500">Pedidos Concluídos</h3>
            </div>
            <div class="ml-5 w-0 flex items-center justify-end flex-1 text-green-500 text-base font-bold">
              {{ completedOrdersPercentageIncrease }}%
              <i class="fa-solid fa-arrow-up ml-1" />
            </div>
          </div>
        </div>
      </Link>

      <Link as="button" href="/orders?status=Entrega%20Agendada">
        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 hover:bg-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{
                ordersByStatus['Entrega Agendada'] }}</span>
              <h3 class="text-base font-normal text-gray-500">Entregas Agendadas</h3>
            </div>
          </div>
        </div>
      </Link>

      <Link as="button" href="/orders?status=Entrega%20Não%20Agendada">
        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 hover:bg-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{
                ordersByStatus['Entrega Não Agendada'] }}</span>
              <h3 class="text-base font-normal text-gray-500">Entregas Não Agendadas</h3>
            </div>
          </div>
        </div>
      </Link>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue'
import SalesChart from './Charts/SalesChart.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

let props = defineProps({
    sales: Array,
    profit: Array,
    clients: Object,
    totalOutstandingBalance: [String, Number],
    ordersByStatus: Object,
    salesPercentageIncrease: [String, Number],
    profitPercentageIncrease: [String, Number],
    completedOrdersPercentageIncrease: [String, Number],
})

const currentMonth = new Date().getMonth() + 1

const saleMonth = props.sales.find(sale => sale.month === currentMonth)
const profitMonth = props.profit.find(profit => profit.month === currentMonth)

const formatCurrency = (value, locale = 'pt-BR', currency = 'BRL') => {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
    }).format(value)
}

const saleCurrentMonth = saleMonth ? formatCurrency(saleMonth.total_sales) : formatCurrency(0)
const profitCurrentMonth = profitMonth ? formatCurrency(profitMonth.total_profit) : formatCurrency(0)

const totalOutstandingBalance = computed(() => {
    return Object.values(props.clients).reduce((total, client) => total + client.outstanding_balance, 0)
})

</script>
