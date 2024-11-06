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
                  v-if="Object.keys({ ...filters, status: undefined }).filter(key => filters[key] !== undefined && key !== 'status').length"
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
              <!-- Nome do cliente -->
              <ClientSelect v-model="selectedClient" :clients="clients" />

              <!-- Data de Criação -->
              <label
                for="start_created_at"
                class="block text-sm font-medium leading-6 text-gray-900 mt-4"
              >Período de Data de
                Criação</label>
              <div class="flex space-x-2 items-baseline">
                <InputField
                  id="start_created_at" v-model="formFilters.start_created_at" name="start_created_at" type="date"
                  wrapper-class="mt-0" class="mt-2"
                />
                <p class="ml-2 mr-2 text-sm">até</p>
                <InputField
                  id="end_created_at" v-model="formFilters.end_created_at" name="end_created_at" type="date"
                  wrapper-class="mt-0"
                />
              </div>

              <!-- Data de Agendamento -->
              <label
                for="start_scheduled_date"
                class="block text-sm font-medium leading-6 text-gray-900 mt-4"
              >Período de Data de
                Agendamento</label>
              <div class="flex space-x-2 items-baseline">
                <InputField
                  id="start_scheduled_date" v-model="formFilters.start_scheduled_date" name="start_scheduled_date"
                  type="date" wrapper-class="mt-0" class="mt-2"
                />
                <p class="ml-2 mr-2 text-sm">até</p>
                <InputField
                  id="end_scheduled_date" v-model="formFilters.end_scheduled_date" name="end_scheduled_date" type="date"
                  wrapper-class="mt-0"
                />
              </div>

              <!-- Data de Entrega -->
              <label
                for="start_actual_date"
                class="block text-sm font-medium leading-6 text-gray-900 mt-4"
              >Período de Data de
                Entrega</label>
              <div class="flex space-x-2 items-baseline">
                <InputField
                  id="start_actual_date" v-model="formFilters.start_actual_date" name="start_actual_date" type="date"
                  wrapper-class="mt-0" class="mt-2"
                />
                <p class="ml-2 mr-2 text-sm">até</p>
                <InputField
                  id="end_actual_date" v-model="formFilters.end_actual_date" name="end_actual_date" type="date"
                  wrapper-class="mt-0"
                />
              </div>

              <!-- Filtros aplicados -->
              <div class="mt-5">
                <p
                  v-if="Object.keys({ ...filters, status: undefined }).filter(key => filters[key] !== undefined && key !== 'status').length"
                  class="block text-sm font-medium mb-2"
                >
                  Filtros aplicados:
                </p>

                <RemoveFilterButton
                  label="Cliente" :filter-value="filters.client"
                  :get-display-value="getClientName" @remove-filter="clearFilter('client')"
                />

                <RemoveFilterButton
                  label="Data de Início (Criação)"
                  :filter-value="filters.start_created_at" :get-display-value="formatDate"
                  @remove-filter="clearFilter('start_created_at')"
                />

                <RemoveFilterButton
                  label="Data de Fim (Criação)" :filter-value="filters.end_created_at"
                  :get-display-value="formatDate" @remove-filter="clearFilter('end_created_at')"
                />

                <RemoveFilterButton
                  label="Data de Início (Agendamento)"
                  :filter-value="filters.start_scheduled_date" :get-display-value="formatDate"
                  @remove-filter="clearFilter('start_scheduled_date')"
                />

                <RemoveFilterButton
                  label="Data de Fim (Agendamento)"
                  :filter-value="filters.end_scheduled_date" :get-display-value="formatDate"
                  @remove-filter="clearFilter('end_scheduled_date')"
                />

                <RemoveFilterButton
                  label="Data de Início (Entrega)"
                  :filter-value="filters.start_actual_date" :get-display-value="formatDate"
                  @remove-filter="clearFilter('start_actual_date')"
                />

                <RemoveFilterButton
                  label="Data de Início (Entrega)"
                  :filter-value="filters.end_actual_date" :get-display-value="formatDate"
                  @remove-filter="clearFilter('end_actual_date')"
                />
              </div>

              <div class="flex justify-end mt-4">
                <SaveButton type="submit">Salvar</SaveButton>
              </div>
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
import ClientSelect from '@/Components/inputs/ClientSelect.vue'
import RemoveFilterButton from '@/Components/utils/RemoveFilterButton.vue'
import InputField from '@/Components/inputs/InputField.vue'
import SaveButton from '@/Components/actions/SaveButton.vue'

const props = defineProps({
    isVisible: Boolean,
    clients: Array,
    filters: Object,
})

const emit = defineEmits(['update:isVisible', 'apply-filters'])

const selectedClient = ref(props.filters.client || null)

const { formatDate } = useDateUtils()

const formFilters = ref({
    start_created_at: props.filters.start_created_at || '',
    end_created_at: props.filters.end_created_at || '',
    start_scheduled_date: props.filters.start_scheduled_date || '',
    end_scheduled_date: props.filters.end_scheduled_date || '',
    start_actual_date: props.filters.start_actual_date || '',
    end_actual_date: props.filters.end_actual_date || '',
})

const getClientName = (clientId) => {
    const client = props.clients.find(c => c.id === Number(clientId))
    return client ? client.name : ''
}

const closeModal = () => {
    emit('update:isVisible', false)
}

const toast = useToast()

const validateDates = (start_date, end_date) => {
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
    const { start_created_at, end_created_at, start_scheduled_date, end_scheduled_date, start_actual_date, end_actual_date } = formFilters.value

    if (!validateDates(start_created_at, end_created_at) || !validateDates(start_scheduled_date, end_scheduled_date) || !validateDates(start_actual_date, end_actual_date)) {
        return
    }

    const combinedFilters = {
        ...formFilters.value,
        client: selectedClient.value,
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
    if (filter === 'client') {
        selectedClient.value = null
    } else {
        formFilters.value[filter] = ''
    }

    saveFilters(true, true)
}

const clearFilters = () => {
    selectedClient.value = null

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
