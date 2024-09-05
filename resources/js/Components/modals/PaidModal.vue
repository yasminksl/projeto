<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Adicionar Pagamento</h2>
                    <form @submit.prevent="saveOrderPayment">

                        <ModalItem label="Valor pago" id="amount_paid" type="number" v-model.number="amountPaid"
                            placeholder="Digite o valor" step="0.01" />

                        <StatusSelect v-model="paymentMethod" :options="statusOptions" required />

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
import StatusSelect from '../inputs/StatusSelect.vue';
import ModalItem from '../utils/ModalItem.vue';

const props = defineProps({
    isVisible: Boolean,
    orderId: Number,
});

const statusOptions = [
    { value: 'Crédito', text: 'Crédito' },
    { value: 'Débito', text: 'Débito' },
    { value: 'Dinheiro', text: 'Dinheiro' },
    { value: 'Pix', text: 'Pix' },
];

const emit = defineEmits(['update:payment', 'update:isVisible']);

const amountPaid = ref(0);
const paymentMethod = ref('');

const closeModal = () => {
    emit('update:isVisible', false);
};

const toast = useToast();

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
                });

                closeModal();
                router.reload();
                toast.success("Pagamento adicionado com sucesso!");
            },
            onError: (errors) => {
                if(!amountPaid.value || !paymentMethod.value){
                toast.error('Todos os campos são obrigatórios');
                } else {
                    toast.error('Erro ao adicionar pagamento');
                }
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
