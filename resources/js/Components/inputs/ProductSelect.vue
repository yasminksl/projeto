<template>
    <div class="relative">
        <div @click="toggleDropdown"
            class="flex justify-between items-center border border-gray-300 rounded-md shadow-sm cursor-pointer p-3 mt-1">
            <span v-if="selectedProduct">{{ selectedProduct.name }}</span>
            <span v-else>Selecionar Produto</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>

        <!-- Dropdown menu -->
        <div v-if="isDropdownOpen"
            class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">
            <!-- Input de pesquisa -->
            <input type="text" v-model="searchTerm" placeholder="Pesquisar produtos..."
                class="w-full p-2 border-b border-gray-300 rounded-t-md" />

            <!-- Lista de produtos -->
            <ul class="max-h-60 overflow-y-auto">
                <li v-for="product in filteredProducts" :key="product.id" @click="selectProduct(product)"
                    class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-gray-100">
                    <span class="ml-3 block truncate">{{ product.name }}</span>
                    <span v-if="product === selectedProduct"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
            </ul>

            <!-- Mensagem de nenhum produto encontrado -->
            <div v-if="filteredProducts.length === 0 && searchTerm" class="p-2 text-gray-500">
                Nenhum produto encontrado
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    products: Array,
    modelValue: Object,
    modelQuantity: Number
});

const emit = defineEmits(['update:modelValue', 'update:quantity', 'addProduct', 'removeProduct']);

const searchTerm = ref('');
const isDropdownOpen = ref(false);
const selectedProduct = ref(props.modelValue);

const filteredProducts = computed(() => {
    if (!searchTerm.value) return props.products;
    return props.products.filter(product =>
        product.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const selectProduct = (product) => {
    selectedProduct.value = product;
    emit('update:modelValue', product);
    searchTerm.value = '';
    isDropdownOpen.value = false;
};

watch(() => props.modelValue, (newValue) => {
    selectedProduct.value = newValue;
});

watch(() => props.modelQuantity, (newQuantity) => {
    emit('update:quantity', newQuantity);
});
</script>
