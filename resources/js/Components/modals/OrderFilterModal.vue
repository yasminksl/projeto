<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold">Filtros</h2>
                    <form @submit.prevent="saveFilters()">
                        <ClientSelect v-model="selectedClient" :clients="clients" />

                        <ModalItem label="Data do Pedido" id="created_at" type="date"
                            v-model="formFilters.created_at" />

                        <ModalItem label="Data de Agendamento" id="scheduled_delivery_date" type="date"
                            v-model="formFilters.scheduled_delivery_date" />

                        <ModalItem label="Data de Entrega" id="actual_delivery_date" type="date"
                            v-model="formFilters.actual_delivery_date" />

                        <div class="mt-5">
                            <p class="block text-sm font-medium mb-2 ml-2" v-if="Object.keys(filters).length">Filtros
                                aplicados:</p>

                            <RemoveFilterButton label="Cliente" :filterValue="filters.client"
                                :getDisplayValue="getClientName" @remove-filter="clearFilter('client')" />

                            <RemoveFilterButton label="Data do Pedido" :filterValue="filters.created_at"
                                :getDisplayValue="formatDate" @remove-filter="clearFilter('created_at')" />

                            <RemoveFilterButton label="Data de Agendamento"
                                :filterValue="filters.scheduled_delivery_date" :getDisplayValue="formatDate"
                                @remove-filter="clearFilter('scheduled_delivery_date')" />

                            <RemoveFilterButton label="Data de Entrega" :filterValue="filters.actual_delivery_date"
                                :getDisplayValue="formatDate" @remove-filter="clearFilter('actual_delivery_date')" />

                            <p @click="clearFilters" class="cursor-pointer block text-sm font-medium mt-2 ml-2"
                                v-if="Object.keys(filters).length">Limpar todos os filtros</p>
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
import { formatDate } from '@/Composables/useUtils';
import { useToast } from 'vue-toastification';
import ClientSelect from '../inputs/ClientSelect.vue';
import ModalItem from '../utils/ModalItem.vue';
import RemoveFilterButton from '../utils/RemoveFilterButton.vue';

const props = defineProps({
    isVisible: Boolean,
    clients: Array,
    filters: Object
});

const emit = defineEmits(['update:isVisible', 'apply-filters']);

const selectedClient = ref(props.filters.client || null);

const formFilters = ref({
    created_at: props.filters.created_at || '',
    scheduled_delivery_date: props.filters.scheduled_delivery_date || '',
    actual_delivery_date: props.filters.actual_delivery_date || ''
});

const getClientName = (clientId) => {
    const client = props.clients.find(c => c.id === Number(clientId));
    return client ? client.name : '';
};

const closeModal = () => {
    emit('update:isVisible', false);
};

const toast = useToast();

const saveFilters = (toastRemove, noClose) => {
    const combinedFilters = {
        ...formFilters.value,
        client: selectedClient.value
    };
    emit('apply-filters', combinedFilters);

    if (toastRemove) {
        toast.success("Filtro(s) removido(s) com sucesso");
    }

    if (!noClose) {
        closeModal()
    }
}

const clearFilter = (filter) => {
    if (filter === 'client') {
        selectedClient.value = null;
    } else {
        formFilters.value[filter] = '';
    }

    saveFilters(true, true);
};

const clearFilters = () => {
    selectedClient.value = null;

    Object.keys(formFilters.value).forEach(key => {
        formFilters.value[key] = '';
    });

    saveFilters(true);
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
