<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white  p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold">Atualizar Datas</h2>
                    <form @submit.prevent="saveOrderDates">

                        <ModalItem label="Data de Agendamento" id="scheduled_delivery_date" type="date"
                            v-model="scheduledDeliveryDate" />

                        <ModalItem label="Data de Conclusão" id="actual_delivery_date" type="date"
                            v-model="actualDeliveryDate" />

                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" @click="closeModal"
                                class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import ModalItem from '../utils/ModalItem.vue';

const props = defineProps({
    initialScheduledDate: String,
    initialActualDate: String,
    isVisible: Boolean,
    orderId: Number,
});

const emit = defineEmits(['update:dates', 'update:isVisible']);

const scheduledDeliveryDate = ref(props.initialScheduledDate);
const actualDeliveryDate = ref(props.initialActualDate);

const closeModal = () => {
    emit('update:isVisible', false);
};

const toast = useToast();

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
                });

                closeModal();

                toast.success("Datas atualizadas com sucesso!");
            },
            onError: (errors) => {
                toast.error('Erro ao salvar datas');
            }
        });
    } catch (error) {
        console.error('Erro ao salvar:', error);
    }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.modal-content {
    max-height: 80vh;
    overflow-y: auto;
}
</style>
