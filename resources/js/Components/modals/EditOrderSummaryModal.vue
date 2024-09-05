<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Editar Valores</h2>
                    <form @submit.prevent="saveOrderValues">

                        <ModalItem label="Desconto" id="discount" type="number" v-model.number="discount"
                            placeholder="Digite o valor" step="0.01" />

                        <ModalItem label="Juros" id="interest" type="number" v-model.number="interest"
                            placeholder="Digite o valor" step="0.01" />

                        <ModalItem label="Valor pago" id="amount_paid" type="number" v-model.number="amountPaid"
                            placeholder="Digite o valor" step="0.01" />

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

const toast = useToast();

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
                });

                closeModal();

                toast.success("Valores atualizados com sucesso");
            },
            onError: (errors) => {
                toast.error('Erro ao salvar valores');
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
