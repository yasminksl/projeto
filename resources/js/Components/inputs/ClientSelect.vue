<template>
    <div class="relative">
        <div>
            <label for="client_name" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Nome do
                Cliente</label>

            <div @click="toggleDropdown"
                class="flex justify-between items-center mt-1 cursor-pointer p-2 border-gray-300  bg-white w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0">
                <span v-if="selectedClient">{{ selectedClient.name }}</span>
                <span v-else class="text-gray-400">Selecionar Cliente</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>

        <div v-if="isDropdownOpen"
            class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">

            <input type="text" v-model="searchTerm" placeholder="Pesquisar clientes..."
                class="w-full p-2 border-b border-gray-300 rounded-t-md" />

            <ul
                class="absolute z-10 w-full mt-1 bg-white border-gray-300 max-h-60 overflow-y-auto block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0">
                <li v-for="client in filteredClients" :key="client.id" @click="selectClient(client)"
                    class="cursor-pointer select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-gray-200">
                    <span class="ml-3 block truncate">{{ client.name }}</span>
                    <span v-if="client === selectedClient"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-800">
                        <i class="fa-solid fa-check"></i>
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
