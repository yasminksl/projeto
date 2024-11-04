<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">

                    <div class="border-b border-gray-200 p-4 flex justify-between">
                        <div class="flex items-start space-x-3">
                            <i class="fa-regular fa-address-book border border-gray-200 p-3 shadow-sm rounded-md"></i>
                            <div>
                                <h2 class="text-lg font-semibold">Editar Cliente</h2>
                                <p class="text-sm text-gray-500">Edite o cliente do pedido.</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal"></i>
                    </div>

                    <div class="p-4">
                        <ClientSelect v-model="selectedClient" :clients="filteredClients" />
                    </div>

                    <div class="flex justify-end p-4 mt-4">
                        <SaveButton @click="saveClient" type="submit">Salvar</SaveButton>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';
import ClientSelect from '../inputs/ClientSelect.vue';
import SaveButton from '../actions/SaveButton.vue';

const props = defineProps({
    isVisible: Boolean,
    orderClient: Object,
    clients: Array,
    orderId: [String, Number],
});

const emit = defineEmits(['update:order-client', 'update:isVisible']);

const closeModal = () => {
    emit('update:isVisible', false);
};

const selectedClient = ref(props.orderClient.id || null);

const filteredClients = computed(() => {
    return props.clients.filter(client => client.id !== props.orderClient.id);
});

const toast = useToast();

const saveClient = async () => {

    try {
        router.post('/orders/update-order-client', {
            order_id: props.orderId,
            client_id: selectedClient.value,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                emit('update:order-client', selectedClient.value);
                closeModal();
                toast.success("Cliente alterado com sucesso!");
            },
            onError: (errors) => {
                toast.error('Erro ao salvar cliente');
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
