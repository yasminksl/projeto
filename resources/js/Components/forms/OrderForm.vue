<template>
  <div class="min-h-screen bg-gray-100">
    <div class="p-6 bg-white shadow-md rounded-lg mb-6">
      <form @submit.prevent="submit">
        <!-- Seleção do Cliente -->
        <ClientSelect v-if="clients" v-model="selectedClient" :clients="clients" />

        <!-- Produtos -->
        <div class="mb-4 mt-4">
          <label for="products" class="block text-sm font-medium leading-6 text-gray-900">Produtos</label>
          <div
            v-for="(product, index) in selectedProducts" :key="index"
            class="flex items-center justify-content: flex-start mb-4 mt-1 space-x-2"
          >
            <ProductSelect
              :products="products" :model-value="product"
              class="flex-1 h-12" @update:model-value="updateProduct(index, $event)"
            />
            <input
              v-model.number="product.quantity" type="number" placeholder="Quantidade"
              class="w-24 h-12 p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button type="button" class="ml-2 text-red-500" @click="removeProduct(index)">
              Remover
            </button>
          </div>
          <AddButton value="Adicionar Produto" @click="addProduct" />
        </div>

        <!-- Status -->
        <StatusSelect v-model="form.status" label="Status da Entrega" :options="statusOptions" />

        <div v-if="form.status === 'Entrega Agendada'" class="mb-4">
          <InputField
            id="scheduled_delivery_date" v-model="form.scheduled_delivery_date"
            label="Data da Entrega" type="date" required
          />
        </div>

        <TextAreaField
          id="comments" v-model="form.comments" wrapper-class="mb-4"
          name="comments" label="Comentários/Anotações (Opcional)"
        />

        <!-- Resumo do Pedido -->
        <CreateOrderSummary
          :selected-products="selectedProducts" @update:final-amount="updateFinalAmount"
          @update:discount="updateDiscount" @update:interest="updateInterest"
        />

        <!-- Botões de Ação -->
        <div class="flex justify-end mt-5">
          <SaveButton type="submit" :disabled="form.processing" />
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useToast } from 'vue-toastification'
import { router, useForm } from '@inertiajs/vue3'
import ClientSelect from '@/Components/inputs/ClientSelect.vue'
import ProductSelect from '@/Components/inputs/ProductSelect.vue'
import TextAreaField from '@/Components/inputs/TextAreaField.vue'
import StatusSelect from '@/Components/inputs/StatusSelect.vue'
import AddButton from '@/Components/actions/AddButton.vue'
import CreateOrderSummary from '@/Components/sections/CreateOrderSummary.vue'
import InputField from '@/Components/inputs/InputField.vue'
import SaveButton from '@/Components/actions/SaveButton.vue'

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
            interest: '',
        }),
    },
    clients: Array,
    products: Array,
})

const statusOptions = [
    { value: 'Entrega Não Agendada', text: 'Entrega Não Agendada' },
    { value: 'Entrega Agendada', text: 'Entrega Agendada' },
]

const emit = defineEmits(['submit'])

const selectedClient = ref(props.initialValues.client_id || null)
const selectedProducts = ref(props.initialValues.products)

const form = useForm({ ...props.initialValues, client_id: selectedClient.value, products: selectedProducts.value })

watch(selectedClient, (newClient) => {
    form.client_id = newClient
})

const addProduct = () => {
    selectedProducts.value.push({ id: null, quantity: 1 })
}

const removeProduct = (index) => {
    selectedProducts.value.splice(index, 1)
}

const updateProduct = (index, product) => {
    selectedProducts.value[index] = {
        ...product,
        quantity: selectedProducts.value[index]?.quantity,
    }
}

const updateFinalAmount = (amount) => {
    form.total_amount = amount
}

const updateDiscount = (discount) => {
    form.discount = discount
}

const updateInterest = (interest) => {
    form.interest = interest
}

const toast = useToast()

const validateForm = () => {
    const errors = []

    if (!form.client_id) errors.push('cliente')
    if (!form.total_amount) errors.push('total do pedido')
    if (!form.status) errors.push('status do pedido')
    if (!form.products.length) errors.push('produtos')

    return errors
}

const submit = () => {
    const missingFields = validateForm()

    if (missingFields.length > 0) {
        toast.error(`Por favor, preencha as seguintes informações: ${missingFields.join(', ')}.`)
        return
    }

    router.post('/orders', form.data(), {
        onSuccess: () => {
            emit('submit')
            toast.success('Pedido cadastrado com sucesso!')
        },
        onError: () => {
            toast.error('Erro ao enviar o formulário.')
        },
    })
}

</script>
