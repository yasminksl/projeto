<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Filtros</h2>
                    <form @submit.prevent="saveFilters()">
                        <ClientSelect v-model="selectedClient" :clients="clients" />
                        <div class="mt-4">
                            <label for="created_at" class="block text-sm font-medium dark:text-white">Data do Pedido</label>
                            <input type="date" id="created_at" v-model="formFilters.created_at"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div class="mt-4">
                            <label for="scheduled_delivery_date"
                                class="block text-sm font-medium dark:text-white">Data de Agendamento</label>
                            <input type="date" id="scheduled_deliveivery_dry_date"
                                v-model="formFilters.scheduled_delate"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div class="mt-4">
                            <label for="actual_delivery_date" class="block text-sm font-medium dark:text-white">Data de Entrega</label>
                            <input type="date" id="actual_delivery_date" v-model="formFilters.actual_delivery_date"
                                class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div class="mt-5">
                            <p class="block text-sm font-medium dark:text-white mb-2 ml-2"
                                v-if="Object.keys(filters).length">Filtros aplicados:</p>
                            <button v-if="filters.client" @click.prevent="clearFilter('client')"
                                class="bg-gray-100 px-3 py-2 rounded ml-2 flex items-center hover:bg-gray-200 transition-all">
                                <i class="fa-solid fa-xmark mr-2"></i>
                                Cliente: {{ getClientName(filters.client) }}
                            </button>
                            <button v-if="filters.created_at" @click.prevent="clearFilter('created_at')"
                                class="bg-gray-100 px-3 py-2 rounded ml-2 flex items-center hover:bg-gray-200 transition-all mt-2">
                                <i class="fa-solid fa-xmark mr-2"></i>
                                Data do Pedido: {{ formatDate(filters.created_at) }}
                            </button>
                            <button v-if="filters.scheduled_delivery_date"
                                @click.prevent="clearFilter('scheduled_delivery_date')"
                                class="bg-gray-100 px-3 py-2 rounded ml-2 flex items-center hover:bg-gray-200 transition-all mt-2">
                                <i class="fa-solid fa-xmark mr-2"></i>
                                Data de Agendamento: {{ formatDate(filters.scheduled_delivery_date) }}
                            </button>
                            <button v-if="filters.actual_delivery_date"
                                @click.prevent="clearFilter('actual_delivery_date')"
                                class="bg-gray-100 px-3 py-2 rounded ml-2 flex items-center hover:bg-gray-200 transition-all mt-2">
                                <i class="fa-solid fa-xmark mr-2"></i>
                                Data de Entrega: {{ formatDate(filters.actual_delivery_date) }}
                            </button>
                            <p @click="clearFilters" class="cursor-pointer block text-sm font-medium mt-2 ml-2" v-if="Object.keys(filters).length">Limpar todos os filtros</p>
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
import { router } from '@inertiajs/vue3';
import ClientSelect from '../inputs/ClientSelect.vue';

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

const saveFilters = (toastRemove) => {
    const combinedFilters = {
        ...formFilters.value,
        client: selectedClient.value
    };
    emit('apply-filters', combinedFilters);

    if (toastRemove) {
        toast.success("Filtro(s) removido(s) com sucesso");
    }
};

const clearFilter = (filter) => {
    if (filter === 'client') {
        selectedClient.value = null;
    } else {
        formFilters.value[filter] = '';
    }

    saveFilters(true);
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
.fade-leave-to

/* .fade-leave-active in <2.1.8 */
    {
    opacity: 0;
}
</style>
