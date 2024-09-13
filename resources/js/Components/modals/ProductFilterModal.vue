<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold">Filtros</h2>
                    <form @submit.prevent="saveFilters()">

                        <ModalItem label="Descrição (busque por uma palavra)" id="description" v-model="formFilters.description" />

                        <label for="start_date" class="block text-sm font-medium mt-4">Período de Data de Criação</label>
                        <div class="flex space-x-2 items-baseline">
                            <ModalItem id="start_date" type="date"
                                v-model="formFilters.start_date" wrapperClass="mt-0" />
                            <p class="ml-2 mr-2">até</p>
                            <ModalItem id="end_date" type="date" v-model="formFilters.end_date" wrapperClass="mt-0" />
                        </div>

                        <div class="mt-5">
                            <p class="block text-sm font-medium mb-2 ml-2" v-if="Object.keys(filters).length">Filtros
                                aplicados:</p>

                            <RemoveFilterButton label="Descrição" :filterValue="filters.description"
                                @remove-filter="clearFilter('description')" />
                            <RemoveFilterButton label="Data de Início" :filterValue="filters.start_date"
                                @remove-filter="clearFilter('start_date')" :getDisplayValue="formatDate" />
                            <RemoveFilterButton label="Data de Fim" :filterValue="filters.end_date"
                                @remove-filter="clearFilter('end_date')" :getDisplayValue="formatDate" />

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
import ModalItem from '../utils/ModalItem.vue';
import RemoveFilterButton from '../utils/RemoveFilterButton.vue';

const props = defineProps({
    isVisible: Boolean,
    filters: Object
});

const { formatDate } = useDateUtils();

const emit = defineEmits(['update:isVisible', 'apply-filters']);

const formFilters = ref({
    description: props.filters.description || '',
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
