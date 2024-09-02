<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Editar Valores</h2>
                    <form @submit.prevent="submitForm">
                        <div class="mt-4">
                            <label for="discount" class="block text-sm font-medium dark:text-white">Desconto</label>
                            <input type="number" v-model.number="discount" id="discount" placeholder="Digite o valor"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                step="0.01" />
                        </div>
                        <div class="mt-4">
                            <label for="interest" class="block text-sm font-medium dark:text-white">Juros</label>
                            <input type="number" v-model.number="interest" id="interest" placeholder="Digite o valor"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                step="0.01" />
                        </div>
                        <div class="mt-4">
                            <label for="amount_paid" class="block text-sm font-medium dark:text-white">Valor
                                pago</label>
                            <input type="number" v-model.number="amountPaid" id="amount_paid"
                                placeholder="Digite o valor"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                step="0.01" />
                        </div>
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

const props = defineProps({
    isVisible: Boolean,
    order: Object,
});

const emit = defineEmits(['update:order-values', 'update:isVisible']);

const discount = ref(props.order.discount);
const interest = ref(props.order.interest);
const amountPaid = ref(props.order.amount_paid);

const closeModal = () => {
    emit('update:isVisible', false);
};

const submitForm = async () => {
    try {
        const response = await axios.post('/orders/update-order-values', {
            order_id: props.order.id,
            discount: discount.value,
            interest: interest.value,
            amountPaid: amountPaid.value,
        });

        if (response.status === 200) {
            emit('update:order-values', {
                discount: discount.value,
                interest: interest.value,
                amountPaid: amountPaid.value,
            });
            closeModal();
            window.location.reload();
        }
    } catch (error) {
        console.error('Erro ao salvar:', error);
        alert('Erro ao salvar valores');
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
