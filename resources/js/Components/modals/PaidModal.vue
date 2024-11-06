<template>
  <transition name="fade">
    <div v-if="isVisible" class="fixed inset-0 z-50">
      <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
      <div class="fixed inset-0 flex items-center justify-center">
        <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">
          <div class="border-b border-gray-200 p-4 flex justify-between">
            <div class="flex items-start space-x-3">
              <i class="fa-solid fa-dollar-sign border border-gray-200 p-3 shadow-sm rounded-md" />
              <div>
                <h2 class="text-lg font-semibold">Adicionar pagamento</h2>
                <p class="text-sm text-gray-500">Adicione pagamento ao pedido.</p>
              </div>
            </div>
            <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal" />
          </div>

          <form @submit.prevent="saveOrderPayment">
            <div class="p-4">
              <InputField
                id="amount_paid" v-model.number="amountPaid" wrapper-class="sm:col-span-4"
                name="amount_paid" label="Valor pago" active placeholder="0,00" required
                class="font-bold"
              />

              <StatusSelect
                v-model="paymentMethod" label="Forma de pagamento" :options="statusOptions"
                required class="mt-4"
              />
            </div>

            <div class="flex justify-end p-4">
              <SaveButton type="submit">Salvar</SaveButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import StatusSelect from '../inputs/StatusSelect.vue'
import InputField from '../inputs/InputField.vue'
import SaveButton from '../actions/SaveButton.vue'

const props = defineProps({
    isVisible: Boolean,
    orderId: Number,
})

const statusOptions = [
    { value: 'Crédito', text: 'Crédito' },
    { value: 'Débito', text: 'Débito' },
    { value: 'Dinheiro', text: 'Dinheiro' },
    { value: 'Pix', text: 'Pix' },
]

const emit = defineEmits(['update:payment', 'update:isVisible'])

const amountPaid = ref(0)
const paymentMethod = ref('')

const closeModal = () => {
    emit('update:isVisible', false)
}

const toast = useToast()

const saveOrderPayment = async () => {
    try {
        router.post('/orders/update-payment', {
            order_id: props.orderId,
            amountPaid: amountPaid.value,
            paymentMethod: paymentMethod.value,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                emit('update:payment', {
                    amountPaid: amountPaid.value,
                    paymentMethod: paymentMethod.value,
                })

                closeModal()
                router.reload()
                toast.success('Pagamento adicionado com sucesso!')
            },
            onError: () => {
                if (!amountPaid.value || !paymentMethod.value) {
                    toast.error('Todos os campos são obrigatórios')
                } else {
                    toast.error('Erro ao adicionar pagamento')
                }
            },
        })
    } catch (error) {
        console.error('Erro ao salvar:', error)
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.modal-content {
    max-height: 80vh;
}
</style>
