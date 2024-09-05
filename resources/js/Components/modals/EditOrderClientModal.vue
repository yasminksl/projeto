<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Trocar cliente</h2>
                    <div class="mb-4 mt-4">
                        <ClientSelect v-model="selectedClient" :clients="filteredClients" />
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" @click="closeModal"
                            class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
                        <button @click="saveClient" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
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

    console.log(JSON.stringify({
        order_id: props.orderId,
        client_id: selectedClient.value
    }));

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
