<!-- DateModal.vue -->
<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Atualizar Datas</h2>
                    <form @submit.prevent="saveOrderDates">
                        <div class="mt-4">
                            <label for="scheduled_delivery_date" class="block text-sm font-medium dark:text-white">Data
                                de
                                Agendamento</label>
                            <input type="date" v-model="scheduledDeliveryDate" id="scheduled_delivery_date"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div class="mt-4">
                            <label for="actual_delivery_date" class="block text-sm font-medium dark:text-white">Data de
                                Conclusão</label>
                            <input type="date" v-model="actualDeliveryDate" id="actual_delivery_date"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
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
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

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
.fade-leave-to

/* .fade-leave-active in <2.1.8 */
    {
    opacity: 0;
}
</style>
