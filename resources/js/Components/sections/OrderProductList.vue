<template>
    <div
        class="flex flex-col justify-start items-start bg-gray-50 px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">

        <div class="flex justify-between w-full">
            <p class="text-lg md:text-xl font-semibold leading-6 xl:leading-5 text-gray-800">
                Produtos</p>
            <AddButton value="Adicionar Produto" @click="openModalAddProduct"/>
        </div>

        <div class="mt-4 md:mt-6 flex flex-col md:flex-row justify-start items-start md:items-center md:space-x-6 xl:space-x-8 w-full"
            v-for="product in props.order.products" :key="product.id">
            <div
                class="border-b border-gray-200 md:flex-row flex-col flex justify-between items-start w-full pb-8 space-y-4 md:space-y-0">
                <div class="w-full flex flex-col justify-start items-start space-y-8">
                    <h3 class="text-xl xl:text-2xl font-semibold leading-6 text-gray-800">
                        {{ product.name }}</h3>
                    <div class="flex justify-start items-start flex-col space-y-2">
                        <p class="text-sm leading-none text-gray-800"><span
                                class=" text-gray-300">Descrição: </span>{{
                                    product.description }}</p>
                    </div>
                </div>
                <div class="flex justify-between space-x-8 items-start w-full">
                    <p class="text-base xl:text-lg leading-6">R$ {{ product.price }}</p>
                    <p class="text-base xl:text-lg leading-6 text-gray-800">{{
                        product.pivot.quantity }}</p>
                    <p class="text-base xl:text-lg font-semibold leading-6 text-gray-800">
                        R$ {{ (product.price * product.pivot.quantity).toFixed(2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <AddProductModal :isVisible="isModalAddProductVisible" :orderId="order.id"
        @update:isVisible="isModalAddProductVisible = $event" @update:products="updateProducts" :products="products" :orderProducts="order.products"/>
</template>

<script setup>
import { ref } from 'vue';
import AddProductModal from '../modals/AddProductModal.vue';
import AddButton from '../actions/AddButton.vue';

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

</script>
