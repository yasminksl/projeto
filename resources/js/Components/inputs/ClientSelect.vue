<template>
    <div class="relative">
        <div>
            <label for="client_name" class="block text-sm font-medium leading-6 text-gray-900">Nome do Cliente</label>
            <div @click="toggleDropdown"
                class="flex justify-between items-center border border-gray-300 rounded-md shadow-sm cursor-pointer p-2 mt-1">
                <span v-if="selectedClient">{{ selectedClient.name }}</span>
                <span v-else>Selecionar Cliente</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div v-if="isDropdownOpen"
            class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">
            <input type="text" v-model="searchTerm" placeholder="Pesquisar clientes..."
                class="w-full p-2 border-b border-gray-300 rounded-t-md" />

            <ul class="max-h-60 overflow-y-auto">
                <li v-for="client in filteredClients" :key="client.id" @click="selectClient(client)"
                    class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-gray-100">
                    <span class="ml-3 block truncate">{{ client.name }}</span>
                    <span v-if="client === selectedClient"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd" />
                        </svg>
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
import { ref, computed, watch } from 'vue';

const props = defineProps({
    clients: Array,
    modelValue: [String, Number]
});

const emit = defineEmits(['update:modelValue']);

const searchTerm = ref('');
const isDropdownOpen = ref(false);
const selectedClient = ref(null);

const filteredClients = computed(() => {
    if (!searchTerm.value) return props.clients;
    return props.clients.filter(client =>
        client.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const selectClient = (client) => {
    selectedClient.value = client;
    emit('update:modelValue', client.id);
    searchTerm.value = '';
    isDropdownOpen.value = false;
};

watch(() => props.modelValue, (newValue) => {
    selectedClient.value = props.clients.find(client => client.id === newValue) || null;
});
</script>
