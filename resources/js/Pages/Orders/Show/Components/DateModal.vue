<template>
  <transition name="fade">
    <div v-if="isVisible" class="fixed inset-0 z-50">
      <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
      <div class="fixed inset-0 flex items-center justify-center">
        <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">
          <div class="border-b border-gray-200 p-4 flex justify-between">
            <div class="flex items-start space-x-3">
              <i class="fa-solid fa-calendar-days border border-gray-200 p-3 shadow-sm rounded-md" />
              <div>
                <h2 class="text-lg font-semibold">Atualizar Datas</h2>
                <p class="text-sm text-gray-500">Atualize as datas do pedido.</p>
              </div>
            </div>
            <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal" />
          </div>

          <form @submit.prevent="saveOrderDates">
            <div class="p-4">
              <InputField
                id="scheduled_delivery_date" v-model="scheduledDeliveryDate" wrapper-class="sm:col-span-4"
                type="date" name="scheduled_delivery_date"
                label="Data de Agendamento"
              />

              <InputField
                id="actual_delivery_date" v-model="actualDeliveryDate" wrapper-class="sm:col-span-4"
                type="date" name="actual_delivery_date" label="Data de Conclusão"
                class="mt-4"
              />
            </div>

            <div class="flex justify-end p-4 mt-4">
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
import InputField from '@/Components/inputs/InputField.vue'
import SaveButton from '@/Components/actions/SaveButton.vue'

const props = defineProps({
    initialScheduledDate: String,
    initialActualDate: String,
    isVisible: Boolean,
    orderId: Number,
})

const emit = defineEmits(['update:dates', 'update:isVisible'])

const scheduledDeliveryDate = ref(props.initialScheduledDate)
const actualDeliveryDate = ref(props.initialActualDate)

const closeModal = () => {
    emit('update:isVisible', false)
}

const toast = useToast()

const saveOrderDates = async () => {
    try {
        router.post('/orders/update-dates', {
            order_id: props.orderId,
            scheduledDeliveryDate: scheduledDeliveryDate.value,
            actualDeliveryDate: actualDeliveryDate.value,
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                emit('update:dates', {
                    scheduledDeliveryDate: scheduledDeliveryDate.value,
                    actualDeliveryDate: actualDeliveryDate.value,
                })

                closeModal()

                toast.success('Datas atualizadas com sucesso!')
            },
            onError: () => {
                toast.error('Erro ao salvar datas')
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
