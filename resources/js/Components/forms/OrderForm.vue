<template>
    <div class="min-h-screen bg-gray-100">
        <div class="p-6 bg-white shadow-md rounded-lg mb-6">
            <form @submit.prevent="submit">
                <!-- Seleção do Cliente -->
                <ClientSelect v-model="selectedClient" :clients="clients" v-if="clients" />

                <!-- Produtos -->
                <div class="mb-4 mt-4">
                    <label for="products" class="block text-sm font-medium leading-6 text-gray-900">Produtos</label>
                    <div v-for="(product, index) in selectedProducts" :key="index"
                        class="flex items-center justify-content: flex-start mb-4 mt-1 space-x-2">
                        <ProductSelect :products="products" :modelValue="product"
                            @update:modelValue="updateProduct(index, $event)" class="flex-1 h-12" />
                        <input type="number" v-model.number="product.quantity" placeholder="Quantidade"
                            class="w-24 h-12 p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="button" @click="removeProduct(index)" class="ml-2 text-red-500">
                            Remover
                        </button>
                    </div>
                    <AddButton value="Adicionar Produto" @click="addProduct" />
                </div>

                <!-- Status -->
                <StatusSelect label="Status da Entrega" v-model="form.status" :options="statusOptions" />

                <div v-if="form.status === 'Entrega Agendada'" class="mb-4">
                    <InputField label="Data da Entrega" id="scheduled_delivery_date"
                        v-model="form.scheduled_delivery_date" type="date" required />
                </div>

                <TextAreaField wrapperClass="mb-4" name="comments" id="comments"
                    label="Comentários/Anotações (Opcional)" v-model="form.comments" />

                <!-- Resumo do Pedido -->
                <CreateOrderSummary :selectedProducts="selectedProducts" @update:finalAmount="updateFinalAmount"
                    @update:discount="updateDiscount" @update:interest="updateInterest" />

                <!-- Botões de Ação -->
                <div class="flex justify-end mt-5">
                    <SaveButton type="submit" :disabled="form.processing" />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';
import ClientSelect from '../inputs/ClientSelect.vue';
import ProductSelect from '../inputs/ProductSelect.vue';
import TextAreaField from '../inputs/TextAreaField.vue';
import StatusSelect from '../inputs/StatusSelect.vue';
import AddButton from '../actions/AddButton.vue';
import CreateOrderSummary from '../sections/CreateOrderSummary.vue';
import InputField from '../inputs/InputField.vue';
import CancelButton from '../actions/CancelButton.vue';
import SaveButton from '../actions/SaveButton.vue';

const props = defineProps({
    initialValues: {
        type: Object,
        default: () => ({
            client_id: '',
            total_amount: '',
            status: '',
            scheduled_delivery_date: '',
            comments: '',
            products: [],
            discount: '',
            interest: ''
        })
    },
    clients: Array,
    products: Array
});

const statusOptions = [
    { value: 'Entrega Não Agendada', text: 'Entrega Não Agendada' },
    { value: 'Entrega Agendada', text: 'Entrega Agendada' }
];

const emit = defineEmits(['submit']);

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

const updateDiscount = (discount) => {
    form.discount = discount;
};

const updateInterest = (interest) => {
    form.interest = interest;
};

const toast = useToast();

const validateForm = () => {
    const errors = [];

    if (!form.client_id) errors.push('cliente');
    if (!form.total_amount) errors.push('total do pedido');
    if (!form.status) errors.push('status do pedido');
    if (!form.products.length) errors.push('produtos');

    return errors;
};

const submit = () => {
    const missingFields = validateForm();

    if (missingFields.length > 0) {
        toast.error(`Por favor, preencha as seguintes informações: ${missingFields.join(', ')}.`);
        return;
    }

    router.post('/orders', form.data(), {
        onSuccess: () => {
            emit('submit');
            toast.success('Pedido cadastrado com sucesso!');
        },
        onError: () => {
            toast.error("Erro ao enviar o formulário.");
        }
    });
};

</script>
