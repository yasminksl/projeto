<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">

                    <!-- Título e Limpar todos os filtros -->
                    <div class="border-b border-gray-200 p-4 flex justify-between">
                        <div class="flex items-start space-x-3">
                            <div class="flex items-center space-x-2">
                                <h2 class="text-lg font-semibold">Filtros</h2>
                                <div class="bg-gray-100 p-2 rounded-full shadow-sm flex items-center hover:bg-gray-200 transition-all"
                                    v-if="Object.keys({ ...filters, search: undefined }).filter(key => filters[key] !== undefined && key !== 'search').length">
                                    <p @click="clearFilters" class="cursor-pointer text-sm">
                                        Limpar todos os filtros</p>
                                    <i class="fa-regular fa-circle-xmark text-gray-400 ml-2"></i>
                                </div>
                            </div>
                        </div>
                        <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal"></i>
                    </div>

                    <form @submit.prevent="saveFilters()">

                        <div class="p-4">

                            <!-- CEP -->
                            <InputField label="CEP" id="postal_code" v-model="formFilters.postal_code"
                                wrapperClass="mt-0" @input="formatPostalCode" />

                            <!-- Rua -->
                            <InputField label="Logradouro" id="street_address" v-model="formFilters.street_address"
                                wrapperClass="mt-0" class="mt-2" />

                            <!-- Bairro -->
                            <InputField label="Bairro" id="neighborhood" v-model="formFilters.neighborhood"
                                wrapperClass="mt-0" class="mt-2" />

                            <!-- Cidade -->
                            <InputField label="Cidade" id="city" v-model="formFilters.city" wrapperClass="mt-0"
                                class="mt-2" />

                            <!-- Data de Criação -->
                            <label for="start_date"
                                class="block text-sm font-medium leading-6 text-gray-900 mt-4">Período de Data de
                                Criação</label>
                            <div class="flex space-x-2 items-baseline">
                                <InputField id="start_date" type="date" v-model="formFilters.start_date"
                                    wrapperClass="mt-0" class="mt-2" />
                                <p class="ml-2 mr-2 text-sm">até</p>
                                <InputField id="end_date" type="date" v-model="formFilters.end_date"
                                    wrapperClass="mt-0" />
                            </div>

                            <!-- Filtros aplicados -->
                            <div class="mt-5">
                                <p class="block text-sm font-medium mb-2"
                                    v-if="Object.keys({ ...filters, search: undefined }).filter(key => filters[key] !== undefined && key !== 'search').length">
                                    Filtros aplicados:
                                </p>

                                <RemoveFilterButton label="CEP" :filterValue="filters.postal_code"
                                    @remove-filter="clearFilter('postal_code')" />
                                <RemoveFilterButton label="Logradouro" :filterValue="filters.street_address"
                                    @remove-filter="clearFilter('street_address')" />
                                <RemoveFilterButton label="Bairro" :filterValue="filters.neighborhood"
                                    @remove-filter="clearFilter('neighborhood')" />
                                <RemoveFilterButton label="Cidade" :filterValue="filters.city"
                                    @remove-filter="clearFilter('city')" />
                                <RemoveFilterButton label="Data de Início" :filterValue="filters.start_date"
                                    @remove-filter="clearFilter('start_date')" :getDisplayValue="formatDate" />
                                <RemoveFilterButton label="Data de Fim" :filterValue="filters.end_date"
                                    @remove-filter="clearFilter('end_date')" :getDisplayValue="formatDate" />
                            </div>
                        </div>

                        <div class="flex justify-end p-4">
                            <SaveButton @click="saveClient" type="submit">Salvar</SaveButton>
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
import RemoveFilterButton from '../utils/RemoveFilterButton.vue';
import InputField from '../inputs/InputField.vue';
import SaveButton from '../actions/SaveButton.vue';

const props = defineProps({
    isVisible: Boolean,
    filters: Object
});

const { formatDate } = useDateUtils();

const emit = defineEmits(['update:isVisible', 'apply-filters']);

const formFilters = ref({
    postal_code: props.filters.postal_code || '',
    street_address: props.filters.street_address || '',
    neighborhood: props.filters.neighborhood || '',
    city: props.filters.city || '',
    start_date: props.filters.start_date || '',
    end_date: props.filters.end_date || '',
});

const closeModal = () => {
    emit('update:isVisible', false);
};

const toast = useToast();

const validateDates = () => {
    const { start_date, end_date } = formFilters.value;
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
    if (!validateDates()) {
        return;
    }

    const combinedFilters = {
        ...formFilters.value,
    };

    emit('apply-filters', combinedFilters);

    if (toastRemove) {
        toast.success("Filtro(s) removido(s) com sucesso");
    }

    if (!noClose) {
        closeModal();
    }
};

const formatPostalCode = () => {
    let value = formFilters.value.postal_code.replace(/\D/g, '');
    if (value.length > 5) {
        value = value.replace(/(\d{5})(\d{1,3})/, '$1-$2');
    }
    formFilters.value.postal_code = value;
};

const clearFilter = (filter) => {
    formFilters.value[filter] = '';
    saveFilters(true, true);
};

const clearFilters = () => {
    Object.keys(formFilters.value).forEach(key => {
        formFilters.value[key] = '';
    });
    saveFilters(true);
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
    overflow-y: auto;
}
</style>
