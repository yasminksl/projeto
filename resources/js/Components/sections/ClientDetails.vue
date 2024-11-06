<template>
  <div
    class="bg-gray-50 w-full xl:w-96 flex justify-between items-center md:items-start px-4 py-6 md:p-6 xl:p-8 flex-col"
  >
    <div class="flex justify-between items-center w-full">
      <h3 class="text-xl font-semibold leading-5 text-gray-800">Cliente</h3>
      <EditButton @click="openModalOrderClient" />
    </div>
    <div
      class="flex flex-col md:flex-row xl:flex-col justify-start items-stretch h-full w-full md:space-x-6 lg:space-x-8 xl:space-x-0"
    >
      <div class="flex flex-col justify-start items-start flex-shrink-0">
        <div
          class="flex justify-center w-full md:justify-start items-center space-x-4 py-8 border-b border-gray-200"
        >
          <div class="flex justify-start items-start flex-col space-y-2">
            <p class="text-base font-semibold leading-4 text-left text-gray-800">
              {{ orderClient.name }}
            </p>
          </div>
        </div>
      </div>
      <div class="flex justify-between xl:h-full items-stretch w-full flex-col mt-6 md:mt-0">
        <div
          class="flex justify-center md:justify-start xl:flex-col flex-col md:space-x-6 lg:space-x-8 xl:space-x-0 space-y-4 xl:space-y-12 md:space-y-0 md:flex-row items-center md:items-start"
        >
          <div
            class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4 xl:mt-8"
          >
            <p class="text-base font-semibold leading-4 text-center md:text-left text-gray-800">
              Endereço
            </p>
            <p class="w-48 lg:w-full xl:w-48 text-center md:text-left text-sm leading-5 text-gray-600">
              {{
                orderClient.street_address }}, nº {{ orderClient.address_number }}, {{
                orderClient.neighborhood }}, {{ orderClient.city }}, CEP: {{
                orderClient.postal_code
              }}
            </p>
          </div>
        </div>
        <div class="flex w-full justify-center items-center md:justify-start md:items-start">
          <Link
            :href="`/clients/${orderClient.id}`"
            class="mt-6 md:mt-0 py-5 px-4 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 border border-gray-800 w-96 2xl:w-full text-base font-medium leading-4 text-gray-800 text-center"
          >
            Ver Detalhes
          </Link>
        </div>
      </div>
    </div>
  </div>

  <EditOrderClientModal
    :order-id="orderId" :is-visible="isModalOrderClientVisible" :order-client="orderClient"
    :clients="clients" @update:is-visible="isModalOrderClientVisible = $event"
    @update:order-client="updateOrderClient"
  />
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import EditButton from '../actions/EditButton.vue'
import EditOrderClientModal from '../modals/EditOrderClientModal.vue'

let props = defineProps({
    orderClient: Object,
    clients: Array,
    orderId: [String, Number],
})

defineEmits(['update:order-client'])

const isModalOrderClientVisible = ref(false)

const openModalOrderClient = () => {
    isModalOrderClientVisible.value = true
}

const closeModalOrderClient = () => {
    isModalOrderClientVisible.value = false
}

const localOrderClientId = ref(props.orderClient.id)

const updateOrderClient = (client) => {
    localOrderClientId.value = client
    closeModalOrderClient()
}

</script>
