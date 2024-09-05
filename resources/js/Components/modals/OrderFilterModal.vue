<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold">Filtros</h2>
                    <form @submit.prevent="saveFilters()">
                        <ClientSelect v-model="selectedClient" :clients="clients" />

                        <label for="start_created_at" class="block text-sm font-medium mt-4">Período de Data de
                            Criação</label>
                        <div class="flex space-x-2 items-baseline">
                            <ModalItem id="start_created_at" type="date" v-model="formFilters.start_created_at"
                                wrapperClass="mt-0" />
                            <p class="ml-2 mr-2">até</p>
                            <ModalItem id="end_created_at" type="date" v-model="formFilters.end_created_at"
                                wrapperClass="mt-0" />
                        </div>

                        <label for="start_scheduled_date" class="block text-sm font-medium mt-4">Período de Data de
                            Agendamento</label>
                        <div class="flex space-x-2 items-baseline">
                            <ModalItem id="start_scheduled_date" type="date" v-model="formFilters.start_scheduled_date"
                                wrapperClass="mt-0" />
                            <p class="ml-2 mr-2">até</p>
                            <ModalItem id="end_scheduled_date" type="date" v-model="formFilters.end_scheduled_date"
                                wrapperClass="mt-0" />
                        </div>

                        <label for="start_actual_date" class="block text-sm font-medium mt-4">Período de Data de
                            Entrega</label>
                        <div class="flex space-x-2 items-baseline">
                            <ModalItem id="start_actual_date" type="date" v-model="formFilters.start_actual_date"
                                wrapperClass="mt-0" />
                            <p class="ml-2 mr-2">até</p>
                            <ModalItem id="end_atual_date" type="date" v-model="formFilters.end_atual_date"
                                wrapperClass="mt-0" />
                        </div>

                        <div class="mt-5">
                            <p class="block text-sm font-medium mb-2 ml-2" v-if="Object.keys(filters).length">Filtros
                                aplicados:</p>

                            <RemoveFilterButton label="Cliente" :filterValue="filters.client"
                                :getDisplayValue="getClientName" @remove-filter="clearFilter('client')" />

                            <RemoveFilterButton label="Data de Início (Criação)" :filterValue="filters.start_created_at"
                                :getDisplayValue="formatDate" @remove-filter="clearFilter('start_created_at')" />

                            <RemoveFilterButton label="Data de Fim (Criação)" :filterValue="filters.end_created_at"
                                :getDisplayValue="formatDate" @remove-filter="clearFilter('end_created_at')" />

                            <RemoveFilterButton label="Data de Início (Agendamento)"
                                :filterValue="filters.start_scheduled_date" :getDisplayValue="formatDate"
                                @remove-filter="clearFilter('start_scheduled_date')" />

                            <RemoveFilterButton label="Data de Fim (Agendamento)"
                                :filterValue="filters.end_scheduled_date" :getDisplayValue="formatDate"
                                @remove-filter="clearFilter('end_scheduled_date')" />

                            <RemoveFilterButton label="Data de Início (Entrega)"
                                :filterValue="filters.start_actual_date" :getDisplayValue="formatDate"
                                @remove-filter="clearFilter('start_actual_date')" />

                            <RemoveFilterButton label="Data de Início (Entrega)" :filterValue="filters.end_actual_date"
                                :getDisplayValue="formatDate" @remove-filter="clearFilter('end_actual_date')" />

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
import { useDateUtils } from '@/Composables/useUtils';
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

const { formatDate } = useDateUtils();

const formFilters = ref({
    created_at: props.filters.start_created_at || '',
    created_at: props.filters.end_created_at || '',
    start_scheduled_date: props.filters.start_scheduled_date || '',
    end_scheduled_date: props.filters.end_scheduled_date || '',
    start_actual_date: props.filters.start_actual_date || '',
    end_actual_date: props.filters.end_actual_date || '',
});

const getClientName = (clientId) => {
    const client = props.clients.find(c => c.id === Number(clientId));
    return client ? client.name : '';
};

const closeModal = () => {
    emit('update:isVisible', false);
};

const toast = useToast();

const validateDates = (start_date, end_date) => {
    const start = new Date(start_date);
    const end = new Date(end_date);

    let isValid = true;

    if (start_date && end_date) {
        if (start > end) {
            toast.error('A data de fim não pode ser antes da data de início.');
            isValid = false;
        }
    } else if (start_date || end_date) {
        toast.error('Você deve preencher tanto a data de início quanto a data de fim.');
        isValid = false;
    }

    return isValid;
};

const saveFilters = (toastRemove = false, noClose = false) => {
    const { start_created_at, end_created_at, start_scheduled_date, end_scheduled_date, start_actual_date, end_actual_date } = formFilters.value;

    if (!validateDates(start_created_at, end_created_at) || !validateDates(start_scheduled_date, end_scheduled_date) || !validateDates(start_actual_date, end_actual_date)) {
        return;
    }

    const combinedFilters = {
        ...formFilters.value,
        client: selectedClient.value
    };
    emit('apply-filters', combinedFilters);

    if (toastRemove) {
        toast.success("Filtro(s) removido(s) com sucesso");
    }

    if (!noClose) {
        closeModal();
    }
};


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
