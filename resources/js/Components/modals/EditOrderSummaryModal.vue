<template>
  <transition name="fade">
    <div v-if="isVisible" class="fixed inset-0 z-50">
      <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
      <div class="fixed inset-0 flex items-center justify-center">
        <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">
          <div class="border-b border-gray-200 p-4 flex justify-between">
            <div class="flex items-start space-x-3">
              <i class="fa-solid fa-receipt border border-gray-200 p-3 shadow-sm rounded-md" />
              <div>
                <h2 class="text-lg font-semibold">Editar valores</h2>
                <p class="text-sm text-gray-500">Edite os valores do pedido.</p>
              </div>
            </div>
            <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal" />
          </div>

          <form @submit.prevent="saveOrderValues">
            <div class="p-4">
              <InputField
                id="price" v-model.number="discount" wrapper-class="sm:col-span-4" name="price"
                label="Preço" active placeholder="0,00" required class="font-bold"
              />

              <InputField
                id="interest" v-model.number="interest" wrapper-class="sm:col-span-4" name="interest"
                label="Juros" active placeholder="0,00" required class="mt-4 font-bold"
              />

              <InputField
                id="amount_paid" v-model.number="amountPaid" wrapper-class="sm:col-span-4"
                name="amount_paid" label="Valor pago" active placeholder="0,00" required
                class="mt-4 font-bold"
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
import InputField from '../inputs/InputField.vue'
import SaveButton from '../actions/SaveButton.vue'

const props = defineProps({
    isVisible: Boolean,
    order: Object,
})

const emit = defineEmits(['update:order-values', 'update:isVisible'])

const discount = ref(props.order.discount)
const interest = ref(props.order.interest)
const amountPaid = ref(props.order.amount_paid)

const closeModal = () => {
    emit('update:isVisible', false)
}

const toast = useToast()

const saveOrderValues = async () => {
    try {
        router.post('/orders/update-order-values', {
            order_id: props.order.id,
            discount: discount.value,
            interest: interest.value,
            amountPaid: amountPaid.value,
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                emit('update:order-values', {
                    discount: discount.value,
                    interest: interest.value,
                    amountPaid: amountPaid.value,
                })

                closeModal()

                toast.success('Valores atualizados com sucesso')
            },
            onError: () => {
                toast.error('Erro ao salvar valores')
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
    overflow-y: auto;
}
</style>
