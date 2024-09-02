<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Adicionar Pagamento</h2>
                    <form @submit.prevent="submitForm">
                        <div class="mt-4">
                            <label for="amount_paid" class="block text-sm font-medium dark:text-white">Valor
                                pago</label>
                            <input type="number" v-model.number="amountPaid" id="amount_paid"
                                placeholder="Digite o valor"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                step="0.01" />
                        </div>
                        <StatusSelect v-model="paymentMethod" :options="statusOptions" />
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
import axios from 'axios';
import StatusSelect from '../inputs/StatusSelect.vue';

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

const submitForm = async () => {
    try {
        const response = await axios.post('/orders/update-payment', {
            order_id: props.orderId,
            amountPaid: amountPaid.value,
            paymentMethod: paymentMethod.value,
        });

        if (response.status === 200) {
            emit('update:payment', {
                amountPaid: amountPaid.value,
                paymentMethod: paymentMethod.value,
            });
            closeModal();
            window.location.reload();
        }
    } catch (error) {
        alert('Erro ao salvar pagamento');
    }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to

/* .fade-leave-active in <2.1.8 */
    {
    opacity: 0;
}
</style>
