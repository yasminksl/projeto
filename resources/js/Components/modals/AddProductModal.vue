<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">

                    <div class="border-b border-gray-200 p-4 flex justify-between">
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-bag-shopping border border-gray-200 p-3 shadow-sm rounded-md"></i>
                            <div>
                                <h2 class="text-lg font-semibold">Adicionar produtos</h2>
                                <p class="text-sm text-gray-500">Adicione produtos ao pedido.</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal"></i>
                    </div>

                    <form @submit.prevent="saveOrderProducts">
                        <div class="p-4">
                            <label for="products"
                                class="text-sm font-bold leading-6 text-gray-800 hidden">Produtos</label>

                            <div v-for="(product, index) in selectedProducts" :key="index"
                                class="flex space-x-2 mb-4 items-center">
                                <ProductSelect :products="availableProducts" :modelValue="product"
                                    @update:modelValue="updateProduct(index, $event)" class="flex-1" />
                                <input type="number" v-model.number="product.quantity" placeholder="Quantidade"
                                    class="w-16 h-12 p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0" />
                                <button type="button" @click="removeProduct(index)" class="ml-2 text-red-500 text-sm">
                                    Remover
                                </button>
                            </div>

                            <AddButton value="Adicionar" @click="addProduct" />
                        </div>
                        <div class="flex justify-end p-4">
                            <SaveButton type="submit">Salvar</SaveButton>
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
import AddButton from '../actions/AddButton.vue';
import SaveButton from '../actions/SaveButton.vue';

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
}
</style>
