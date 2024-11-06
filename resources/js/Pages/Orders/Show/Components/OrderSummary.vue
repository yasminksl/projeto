<template>
  <div class="flex flex-col px-4 py-6 md:p-6 xl:p-8 w-full bg-gray-50 space-y-6">
    <!-- Título e editar sumário -->
    <div class="flex justify-between items-center">
      <h3 class="text-xl font-semibold leading-5 text-gray-800">Sumário</h3>
      <EditButton @click="openModalEditOrderSummary" />
    </div>

    <!-- Valores -->
    <div class="flex justify-center items-center w-full space-y-4 flex-col border-gray-200 border-b pb-4">
      <SummaryItem label="Subtotal" :value="`R$ ${subtotal.toFixed(2)}`" />
      <SummaryItem label="Desconto" :value="`- R$ ${order.discount}`" />
      <SummaryItem label="Juros" :value="`+ R$ ${order.interest}`" />
    </div>

    <!-- Valores de pagamento -->
    <SummaryItem label="Total" :value="`R$ ${order.total_amount}`" aditional-class="font-semibold" />
    <SummaryItem
      v-if="order.amount_paid" label="Valor pago" :value="`- R$ ${order.amount_paid}`"
      aditional-class="font-semibold"
    />
    <SummaryItem
      v-if="order.total_amount != order.amount_paid" label="Total a receber"
      :value="`R$ ${(order.total_amount - order.amount_paid).toFixed(2)}`" aditional-class="font-semibold"
    />

    <!-- Adicionar pagamento -->
    <ActionButton
      v-if="order.total_amount != order.amount_paid" icon="fa-solid fa-plus" value="Adicionar Pagamento"
      @click="openModalPayment"
    />

    <!-- Pedido Pago -->
    <div v-if="order.total_amount === order.amount_paid" class="w-full flex justify-center items-center mt-5">
      <p class="bg-green-500 p-3 rounded-xl">Pedido pago</p>
    </div>
  </div>

  <!-- Modal Adicionar Pagamento -->
  <PaidModal
    :is-visible="isModalPaymentVisible" :order-id="order.id" @update:is-visible="isModalPaymentVisible = $event"
    @update:payment="updatePayment"
  />

  <!-- Modal Editar Valores -->
  <EditOrderSummaryModal :is-visible="isModalEditOrderSummaryVisible" :order="order" @update:is-visible="isModalEditOrderSummaryVisible = $event" @update:order-values="updateOrderValues" />
</template>

<script setup>
import { computed, ref } from 'vue'
import PaidModal from '../modals/PaidModal.vue'
import SummaryItem from '../utils/SummaryItem.vue'
import ActionButton from '../actions/ActionButton.vue'
import EditButton from '../actions/EditButton.vue'
import EditOrderSummaryModal from '../modals/EditOrderSummaryModal.vue'

let props = defineProps({
    order: Object,
})

const subtotal = computed(() => {
    return props.order.products.reduce((total, product) => {
        if (!product.pivot || !product.pivot.quantity || !product.price) {
            return total
        }
        return total + (product.pivot.quantity * product.price)
    }, 0)
})

const isModalPaymentVisible = ref(false)
const isModalEditOrderSummaryVisible = ref(false)

const openModalPayment = () => {
    isModalPaymentVisible.value = true
}

const closeModalPayment = () => {
    isModalPaymentVisible.value = false
}

const openModalEditOrderSummary = () => {
    isModalEditOrderSummaryVisible.value = true
}

const closeModalEditOrderSummary = () => {
    isModalEditOrderSummaryVisible.value = false
}

const localOrderAmountPaid = ref(props.order.amount_paid)
const localOrderPaymentMethod = ref(props.order.payment_method)

const updatePayment = (payment) => {
    localOrderAmountPaid.value = payment.amountPaid
    localOrderPaymentMethod.value = payment.payment_method

    closeModalPayment()
}

const localOrderDiscount = ref(props.order.discount)
const localOrderInterest = ref(props.order.interest)

const updateOrderValues = (values) => {
    localOrderDiscount.value = values.discount
    localOrderInterest.value = values.interest
    localOrderAmountPaid.value = values.amountPaid

    closeModalEditOrderSummary()
}

</script>
