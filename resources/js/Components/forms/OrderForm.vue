<template>
    <div class="min-h-screen bg-gray-100 p-4 sm:p-6">
        <div class="p-6 bg-white shadow-md rounded-lg mb-6">
            <form @submit.prevent="submit">
                <!-- Seleção do Cliente -->
                <ClientSelect v-model="selectedClient" :clients="clients" />

                <!-- Produtos -->
                <div class="mb-4 mt-4">
                    <label for="products" class="block text-sm font-medium leading-6 text-gray-900">Produtos</label>
                    <div v-for="(product, index) in selectedProducts" :key="index"
                        class="flex items-center justify-content: flex-start mb-4 mt-1 space-x-2">
                        <ProductSelect :products="products" :modelValue="product"
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


                <!-- Status -->
                <StatusSelect v-model="form.status" :options="statusOptions" />

                <!-- Data da Entrega (Exibido apenas se o status for "Agendada") -->
                <div v-if="form.status === 'Entrega Agendada'" class="mb-4">
                    <OrderInputField label="Data da Entrega" id="delivery_date" v-model="form.delivery_date" type="date"
                        required />
                </div>

                <TextAreaField wrapperClass="mb-4" name="comments" id="comments" label="Comentários/Anotações (Opcional)"
                    v-model="form.comments" />

                <!-- Resumo do Pedido -->
                <OrderSummary :selectedProducts="selectedProducts" @update:finalAmount="updateFinalAmount" />

                <!-- Botões de Ação -->
                <div class="flex justify-between">
                    <button type="submit" :disabled="form.processing"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Salvar Pedido
                    </button>
                    <Link href="/orders"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancelar
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'vue-toastification';
import ClientSelect from '../inputs/ClientSelect.vue';
import ProductSelect from '../inputs/ProductSelect.vue';
import OrderInputField from '../inputs/OrderInputField.vue';
import OrderSummary from '../utils/OrderSummary.vue';
import TextAreaField from '../inputs/TextAreaField.vue';
import StatusSelect from '../sections/StatusSelect.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    initialValues: {
        type: Object,
        default: () => ({
            client_id: '',
            total_amount: '',
            status: '',
            delivery_date: '',
            comments: '',
            products: []
        })
    },
    clients: Array,
    products: Array
});

const statusOptions = [
    { value: 'Entrega Não Agendada', text: 'Entrega Não Agendada' },
    { value: 'Entrega Agendada', text: 'Entrega Agendada' }
];

const emit = defineEmits('submit');

const selectedClient = ref(props.initialValues.client_id || null);
const selectedProducts = ref(props.initialValues.products);

const form = useForm({ ...props.initialValues, client_id: selectedClient.value, products: selectedProducts.value });

watch(selectedClient, (newClient) => {
    form.client_id = newClient;
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


const updateFinalAmount = (amount) => {
    form.total_amount = amount;
};

const toast = useToast();

const submit = () => {
    console.log('submit chamado');
    if (props.initialValues.id) {
        form.patch(`/orders/${props.initialValues.id}`);
        toast.success("Pedido atualizado com sucesso!");
    } else {
        form.post('/orders');
        toast.success("Pedido cadastrado com sucesso!");
    }
    emit('submit');
};
</script>
