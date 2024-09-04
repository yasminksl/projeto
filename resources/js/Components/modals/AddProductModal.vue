<template>
    <transition enter-active-class="transition-opacity duration-500 ease-in-out"
        leave-active-class="transition-opacity duration-500 ease-in-out" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold dark:text-white">Adicionar produtos</h2>
                    <form @submit.prevent="saveOrderProducts">
                        <div class="mb-4 mt-4">
                            <label for="products"
                                class="block text-sm font-medium leading-6 text-gray-900">Produtos</label>
                            <div v-for="(product, index) in selectedProducts" :key="index"
                                class="flex items-center justify-content: flex-start mb-4 mt-1 space-x-2">
                                <ProductSelect :products="availableProducts" :modelValue="product"
                                    @update:modelValue="updateProduct(index, $event)" class="flex-1 h-12" />
                                <!-- Definindo a altura fixa para o seletor de produtos e o input -->
                                <input type="number" v-model.number="product.quantity" placeholder="Quantidade"
                                    class="w-24 h-12 p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                <button type="button" @click="removeProduct(index)" class="ml-2 text-red-500">
                                    Remover
                                </button>
                            </div>
                            <button type="button" @click="addProduct"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Adicionar Produto
                            </button>
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
import { ref, computed } from 'vue';
import axios from 'axios';
import ProductSelect from '../inputs/ProductSelect.vue';

const props = defineProps({
    isVisible: Boolean,
    orderId: Number,
    orderProducts: Array,
    products: Array,
});

const emit = defineEmits(['update:products', 'update:isVisible']);

const closeModal = () => {
    emit('update:isVisible', false);
};

const selectedProducts = ref([]);

const availableProducts = computed(() => {
    return props.products.filter(product => {
        return !props.orderProducts.some(orderProduct => orderProduct.id === product.id) &&
            !selectedProducts.value.some(selectedProduct => selectedProduct.id === product.id);
    });
});

const addProduct = () => {
    selectedProducts.value.push({ id: null, quantity: 1 });
};

const removeProduct = (index) => {
    selectedProducts.value.splice(index, 1);
};

const updateProduct = (index, product) => {
    selectedProducts.value[index] = {
        ...product,
        quantity: selectedProducts.value[index]?.quantity
    };
};

const saveOrderProducts = async () => {
    try {
        const response = await axios.post('/orders/update-products', {
            order_id: props.orderId,
            products: selectedProducts.value,
        });

        if (response.status === 200) {
            emit('update:products', selectedProducts.value);
            closeModal();
            window.location.reload();
        }
    } catch (error) {
        console.error('Erro ao salvar:', error);
        alert('Erro ao salvar produtos');
    }
};

</script>
