<template>
  <transition name="fade">
    <div v-if="isVisible" class="fixed inset-0 z-50">
      <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
      <div class="fixed inset-0 flex items-center justify-center">
        <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">
          <!-- Título e Limpar todos os filtros -->
          <div class="border-b border-gray-200 p-4 flex justify-between">
            <div class="flex items-start space-x-3">
              <div class="flex items-center space-x-2">
                <h2 class="text-lg font-semibold">Filtros</h2>
                <div
                  v-if="Object.keys({ ...filters, search: undefined }).filter(key => filters[key] !== undefined && key !== 'search').length"
                  class="bg-gray-100 p-2 rounded-full shadow-sm flex items-center hover:bg-gray-200 transition-all"
                >
                  <p class="cursor-pointer text-sm" @click="clearFilters">
                    Limpar todos os filtros
                  </p>
                  <i class="fa-regular fa-circle-xmark text-gray-400 ml-2" />
                </div>
              </div>
            </div>
            <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal" />
          </div>

          <form @submit.prevent="saveFilters()">
            <div class="p-4">
              <!-- Descrição -->
              <InputField
                id="description" v-model="formFilters.description" label="Descrição"
                aditional-label="(busque por uma palavra)" wrapper-class="mt-0"
              />

              <!-- Data de Criação -->
              <label
                for="start_date"
                class="block text-sm font-medium leading-6 text-gray-900 mt-4"
              >Período
                de Data de
                Criação</label>
              <div class="flex space-x-2 items-baseline">
                <InputField
                  id="start_date" v-model="formFilters.start_date" type="date"
                  wrapper-class="mt-0" class="mt-2"
                />
                <p class="ml-2 mr-2 text-sm">até</p>
                <InputField
                  id="end_date" v-model="formFilters.end_date" type="date"
                  wrapper-class="mt-0"
                />
              </div>

              <div class="mt-5">
                <p
                  v-if="Object.keys({ ...filters, search: undefined }).filter(key => filters[key] !== undefined && key !== 'search').length"
                  class="block text-sm font-medium mb-2 ml-2"
                >
                  Filtros
                  aplicados:
                </p>

                <RemoveFilterButton
                  label="Descrição" :filter-value="filters.description"
                  @remove-filter="clearFilter('description')"
                />
                <RemoveFilterButton
                  label="Data de Início" :filter-value="filters.start_date"
                  :get-display-value="formatDate" @remove-filter="clearFilter('start_date')"
                />
                <RemoveFilterButton
                  label="Data de Fim" :filter-value="filters.end_date"
                  :get-display-value="formatDate" @remove-filter="clearFilter('end_date')"
                />
              </div>
            </div>

            <div class="flex justify-end p-4">
              <SaveButton type="submit" @click="saveClient">Salvar</SaveButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'
import { useDateUtils } from '@/Composables/useUtils'
import { useToast } from 'vue-toastification'
import RemoveFilterButton from '../utils/RemoveFilterButton.vue'
import InputField from '../inputs/InputField.vue'
import SaveButton from '../actions/SaveButton.vue'

const props = defineProps({
    isVisible: Boolean,
    filters: Object,
})

const { formatDate } = useDateUtils()

const emit = defineEmits(['update:isVisible', 'apply-filters'])

const formFilters = ref({
    description: props.filters.description || '',
    start_date: props.filters.start_date || '',
    end_date: props.filters.end_date || '',
})

const closeModal = () => {
    emit('update:isVisible', false)
}

const toast = useToast()

const validateDates = () => {
    const { start_date, end_date } = formFilters.value
    const start = new Date(start_date)
    const end = new Date(end_date)

    let isValid = true

    if (start_date && end_date) {
        if (start > end) {
            toast.error('A data de fim não pode ser antes da data de início.')
            isValid = false
        }
    } else if (start_date || end_date) {
        toast.error('Você deve preencher tanto a data de início quanto a data de fim.')
        isValid = false
    }

    return isValid
}

const saveFilters = (toastRemove = false, noClose = false) => {
    if (!validateDates()) {
        return
    }

    const combinedFilters = {
        ...formFilters.value,
    }

    emit('apply-filters', combinedFilters)

    if (toastRemove) {
        toast.success('Filtro(s) removido(s) com sucesso')
    }

    if (!noClose) {
        closeModal()
    }
}

const clearFilter = (filter) => {
    formFilters.value[filter] = ''
    saveFilters(true, true)
}

const clearFilters = () => {
    Object.keys(formFilters.value).forEach(key => {
        formFilters.value[key] = ''
    })
    saveFilters(true)
}
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
