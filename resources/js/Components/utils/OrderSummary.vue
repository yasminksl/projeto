<template>
    <div class="mt-5 mb-1">
        <div class="mb-4">
            <OrderInputField label="Desconto (Opcional)" type="number" v-model="discount" placeholder="Digite o desconto"
                @input="updateAmount" />
            <OrderInputField label="Juros (Opcional)" type="number" v-model="interest" placeholder="Digite os juros"
                @input="updateAmount" />
        </div>
    </div>

    <div
        class="flex justify-center md:flex-row flex-col items-stretch w-full space-y-4 md:space-y-0 md:space-x-6 xl:space-x-8 mb-4">
        <div class="flex flex-col px-4 py-6 md:p-6 xl:p-8 w-full bg-gray-100 rounded space-y-6">
            <div class="flex justify-center items-center w-full space-y-4 flex-col border-gray-200 border-b pb-4">
                <div class="flex justify-between w-full">
                    <p class="text-base dark:text-white leading-4 text-gray-800">Subtotal</p>
                    <p class="text-base dark:text-gray-300 leading-4 text-gray-600">R$ {{ totalAmountFormatted }}</p>
                </div>
                <div class="flex justify-between items-center w-full" v-if="discount">
                    <p class="text-base dark:text-white leading-4 text-gray-800">Desconto</p>
                    <p class="text-base dark:text-gray-300 leading-4 text-gray-600">- R${{ discount }}</p>
                </div>
                <div class="flex justify-between items-center w-full" v-if="interest">
                    <p class="text-base dark:text-white leading-4 text-gray-800">Juros</p>
                    <p class="text-base dark:text-gray-300 leading-4 text-gray-600">+ R${{ interest }}</p>
                </div>
            </div>
            <div class="flex justify-between items-center w-full">
                <p class="text-base dark:text-white font-semibold leading-4 text-gray-800">Total</p>
                <p class="text-base dark:text-gray-300 font-semibold leading-4 text-gray-600">R$ {{ finalAmountFormatted
                    }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import OrderInputField from '../inputs/OrderInputField.vue';

const props = defineProps({
    selectedProducts: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['update:finalAmount']);

let discount = ref();
let interest = ref();

const totalAmount = computed(() => {
    return props.selectedProducts.reduce((acc, product) => {
        const price = product?.price ? parseFloat(product.price) : 0;
        const quantity = parseInt(product.quantity, 10) || 1;
        return acc + (price * quantity);
    }, 0);
});

const totalAmountFormatted = computed(() => totalAmount.value.toFixed(2));

const finalAmount = computed(() => {
    const total = totalAmount.value;
    const discountValue = parseFloat(discount.value) || 0;
    const interestValue = parseFloat(interest.value) || 0;

    const final = total - discountValue + interestValue;

    return final.toFixed(2);
});

const finalAmountFormatted = computed(() => finalAmount.value);

const updateAmount = () => {
    emit('update:finalAmount', finalAmountFormatted);
};

watch(() => props.selectedProducts, () => {
    updateAmount();
}, { deep: true });

</script>
