<template>
  <div class="mt-5 mb-1">
    <div class="mb-4">
      <InputField
        id="discount" v-model="discount" label="Desconto (Opcional)" type="number" placeholder="0,00" active
        @input="updateAmount"
      />
      <InputField
        id="interest" v-model="interest" label="Juros (Opcional)" type="number" placeholder="0,00" active
        class="mt-2" @input="updateAmount"
      />
    </div>
  </div>

  <div
    class="flex justify-center md:flex-row flex-col items-stretch w-full space-y-4 md:space-y-0 md:space-x-6 xl:space-x-8 mb-4 border-t-2 border-dashed border-[rgba(128,128,128,0.10)] mt-5"
  >
    <div class="mt-5 flex flex-col px-4 py-6 md:p-6 xl:p-8 w-full bg-gray-100 rounded space-y-6 shadow-sm">
      <div class="flex justify-center items-center w-full space-y-4 flex-col border-gray-200 border-b pb-4">
        <div class="flex justify-between w-full">
          <p class="text-base leading-4 text-gray-600">Subtotal</p>
          <p class="text-base leading-4 text-gray-600">R$ {{ totalAmountFormatted }}</p>
        </div>

        <div v-if="discount" class="flex justify-between items-center w-full">
          <p class="text-base leading-4 text-gray-600">Desconto</p>
          <p class="text-base leading-4 text-gray-600">-R$ {{ discount }}</p>
        </div>

        <div v-if="interest" class="flex justify-between items-center w-full">
          <p class="text-base leading-4 text-gray-600">Juros</p>
          <p class="text-base leading-4 text-gray-600">+R$ {{ interest }}</p>
        </div>
      </div>
      <div class="flex justify-between items-center w-full">
        <p class="text-base font-bold leading-4 text-gray-800">Total</p>
        <p class="text-base font-bold leading-4 text-gray-600">
          R$ {{ finalAmountFormatted
          }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import InputField from '@/Components/inputs/InputField.vue'

const props = defineProps({
    selectedProducts: {
        type: Array,
        required: true,
    },
})

const emit = defineEmits(['update:finalAmount', 'update:discount', 'update:interest'])

let discount = ref()
let interest = ref()

const totalAmount = computed(() => {
    return props.selectedProducts.reduce((acc, product) => {
        const price = product?.price ? parseFloat(product.price) : 0
        const quantity = parseInt(product.quantity, 10) || 1
        return acc + (price * quantity)
    }, 0)
})

const totalAmountFormatted = computed(() => totalAmount.value.toFixed(2))

const finalAmount = computed(() => {
    const total = totalAmount.value
    const discountValue = parseFloat(discount.value) || 0
    const interestValue = parseFloat(interest.value) || 0

    const final = total - discountValue + interestValue

    return final.toFixed(2)
})

const finalAmountFormatted = computed(() => finalAmount.value)

const updateAmount = () => {
    emit('update:finalAmount', finalAmountFormatted)
    emit('update:discount', discount.value)
    emit('update:interest', interest.value)
}

watch(() => props.selectedProducts, () => {
    updateAmount()
}, { deep: true })

</script>
