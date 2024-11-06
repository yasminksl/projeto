<template>
  <div class="relative">
    <div
      class="flex justify-between items-center border border-gray-300 rounded-md shadow-sm cursor-pointer p-3 h-12"
      @click="toggleDropdown"
    >
      <span v-if="selectedProduct">{{ selectedProduct.name }}</span>
      <span v-else>Selecionar Produto</span>
      <i class="fa-solid fa-chevron-down" />
    </div>

    <!-- Dropdown menu -->
    <div
      v-if="isDropdownOpen"
      class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"
    >
      <!-- Input de pesquisa -->
      <input
        v-model="searchTerm" type="text" placeholder="Pesquisar produtos..."
        class="w-full p-2 border-b border-gray-300 rounded-t-md"
      />

      <!-- Lista de produtos -->
      <ul class="max-h-60 overflow-y-auto">
        <li
          v-for="product in filteredProducts" :key="product.id" class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-gray-100"
          @click="selectProduct(product)"
        >
          <span class="ml-3 block truncate">{{ product.name }}</span>
          <span
            v-if="product === selectedProduct"
            class="absolute inset-y-0 right-0 flex items-center pr-4"
          />
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
import { ref, computed, watch } from 'vue'

const props = defineProps({
    products: Array,
    modelValue: Object,
    modelQuantity: Number,
})

const emit = defineEmits(['update:modelValue', 'update:quantity', 'addProduct', 'removeProduct'])

const searchTerm = ref('')
const isDropdownOpen = ref(false)
const selectedProduct = ref(props.modelValue)

const filteredProducts = computed(() => {
    if (!searchTerm.value) return props.products
    return props.products.filter(product =>
        product.name.toLowerCase().includes(searchTerm.value.toLowerCase()),
    )
})

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value
}

const selectProduct = (product) => {
    selectedProduct.value = product
    emit('update:modelValue', product)
    searchTerm.value = ''
    isDropdownOpen.value = false
}

watch(() => props.modelValue, (newValue) => {
    selectedProduct.value = newValue
})

watch(() => props.modelQuantity, (newQuantity) => {
    emit('update:quantity', newQuantity)
})
</script>
