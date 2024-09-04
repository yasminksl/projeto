<template>
    <div class="flex flex-col justify-start items-start bg-gray-50 px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">

        <div class="flex justify-between w-full">
            <p class="text-lg md:text-xl font-semibold leading-6 xl:leading-5 text-gray-800">
                Produtos</p>
            <div class="flex space-x-4">
                <!-- Exibir os botões conforme o estado de edição -->
                <template v-if="isEditing">
                    <button @click="saveChanges" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
                    <button @click="cancelEditing" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
                </template>
                <template v-else>
                    <AddButton value="Adicionar Produto" @click="openModalAddProduct" />
                    <EditButton @click="startEditing" />
                </template>
            </div>
        </div>

        <div class="mt-4 md:mt-6 flex flex-col md:flex-row justify-start items-start md:items-center md:space-x-6 xl:space-x-8 w-full"
            v-for="product in props.order.products" :key="product.id">
            <div
                class="border-b border-gray-200 md:flex-row flex-col flex justify-between items-start w-full pb-8 space-y-4 md:space-y-0">
                <div class="w-full flex flex-col justify-start items-start space-y-8">
                    <h3 class="text-xl xl:text-2xl font-semibold leading-6 text-gray-800">
                        {{ product.name }}</h3>
                    <div class="flex justify-start items-start flex-col space-y-2">
                        <p class="text-sm leading-none text-gray-800"><span class=" text-gray-300">Descrição: </span>{{
                            product.description }}</p>
                    </div>
                </div>
                <div class="flex justify-between space-x-8 items-start w-full">

                    <template v-if="isEditing">
                        <button @click="decrementQuantity(product)"
                            class="h-6 w-6 bg-white border rounded-full">-</button>
                        <p class="text-base xl:text-lg leading-6 text-gray-800">{{ product.pivot.quantity }}</p>
                        <button @click="incrementQuantity(product)"
                            class="h-6 w-6 bg-white border rounded-full">+</button>
                        <p class="text-base xl:text-lg font-semibold leading-6 text-gray-800">
                            R$ {{ (product.price * product.pivot.quantity).toFixed(2) }}
                        </p>
                        <button @click="removeProduct(product)"
                            class="font-medium text-red-600 hover:underline">Remover</button>
                    </template>
                    <template v-else>
                        <p class="text-base xl:text-lg leading-6">R$ {{ product.price }}</p>
                        <p class="text-base xl:text-lg leading-6 text-gray-800">{{ product.pivot.quantity }}</p>
                        <p class="text-base xl:text-lg font-semibold leading-6 text-gray-800">
                            R$ {{ (product.price * product.pivot.quantity).toFixed(2) }}
                        </p>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <AddProductModal :isVisible="isModalAddProductVisible" :orderId="order.id"
        @update:isVisible="isModalAddProductVisible = $event" @update:products="updateProducts" :products="products"
        :orderProducts="order.products" />
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import cloneDeep from 'lodash/cloneDeep';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';
import AddProductModal from '../modals/AddProductModal.vue';
import AddButton from '../actions/AddButton.vue';
import EditButton from '../actions/EditButton.vue';

let props = defineProps({
    products: Array,
    order: Object
});

const isModalAddProductVisible = ref(false);

const openModalAddProduct = () => {
    isModalAddProductVisible.value = true;
};

const closeModal = () => {
    isModalAddProductVisible.value = false;
};

const updateProducts = (products) => {
    props.order.products = products;

    closeModal();
};

const isEditing = ref(false);
const originalProducts = ref(cloneDeep(props.order.products));

const startEditing = () => {
    originalProducts.value = cloneDeep(props.order.products);
    isEditing.value = true;
};

const cancelEditing = () => {
    // props.order.products = cloneDeep(originalProducts.value);
    // isEditing.value = false;
    window.location.reload();
};

const incrementQuantity = (product) => {
    product.pivot.quantity++;
};

const decrementQuantity = (product) => {
    if (product.pivot.quantity > 0) {
        product.pivot.quantity--;
    }
};

const toast = useToast();

const saveChanges = async () => {
    isEditing.value = false;
    let success = true;

    for (const product of props.order.products) {
        try {
            await axios.patch(`/orders/${props.order.id}/products/${product.id}`, {
                quantity: product.pivot.quantity,
            });
        } catch (error) {
            success = false;
            toast.error('Erro ao salvar mudanças');
        }
    }

    if (success) {
        toast.success('Produto(s) atualizado(s) com sucesso');
    }

    originalProducts.value = [...props.order.products];
};

const removeProduct = async (product) => {
    const confirmed = confirm('Você tem certeza que deseja remover este produto?');

    if (!confirmed) {
        return;
    }

    try {
        await axios.delete(`/orders/${props.order.id}/products/${product.id}`);

        router.reload();
        toast.success("Produto removido com sucesso");

        props.order.products = props.order.products.filter(p => p.id !== product.id);
    } catch (error) {
        toast.error('Erro ao remover produto');
    }
};

</script>
