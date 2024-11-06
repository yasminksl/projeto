<template>
  <div class="relative">
    <div>
      <label for="client_name" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Nome do
        Cliente</label>

      <div
        class="btn-dropdown"
        @click="toggleDropdown"
      >
        <span v-if="selectedClient">{{ selectedClient.name }}</span>
        <span v-else class="text-gray-400">Selecionar Cliente</span>
        <i class="fa-solid fa-chevron-down" />
      </div>
    </div>

    <div
      v-if="isDropdownOpen"
      class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"
    >
      <input
        v-model="searchTerm" type="text" placeholder="Pesquisar clientes..."
        class="w-full p-2 border-b border-gray-300 rounded-t-md"
      />

      <ul
        class="dropdown-list max-h-60 overflow-y-auto"
      >
        <li
          v-for="client in filteredClients" :key="client.id" class="cursor-pointer select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-gray-200"
          @click="selectClient(client)"
        >
          <span class="ml-3 block truncate">{{ client.name }}</span>
          <span
            v-if="client === selectedClient"
            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-800"
          >
            <i class="fa-solid fa-check" />
          </span>
        </li>
      </ul>

      <div v-if="filteredClients.length === 0 && searchTerm" class="p-2 text-gray-500">
        Nenhum cliente encontrado
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    clients: Array,
    modelValue: [String, Number],
})

const emit = defineEmits(['update:modelValue'])

const searchTerm = ref('')
const isDropdownOpen = ref(false)
const selectedClient = ref(null)

const filteredClients = computed(() => {
    if (!searchTerm.value) return props.clients
    return props.clients.filter(client =>
        client.name.toLowerCase().includes(searchTerm.value.toLowerCase()),
    )
})

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value
}

const selectClient = (client) => {
    selectedClient.value = client
    emit('update:modelValue', client.id)
    searchTerm.value = ''
    isDropdownOpen.value = false
}

watch(() => props.modelValue, (newValue) => {
    selectedClient.value = props.clients.find(client => client.id === newValue) || null
})
</script>
