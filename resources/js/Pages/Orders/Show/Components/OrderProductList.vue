<template>
  <div class="flex flex-col justify-start items-start bg-gray-50 px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">
    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between w-full">
      <!-- Título -->
      <p class="text-lg md:text-xl font-semibold leading-6 xl:leading-5 text-gray-800">
        Produtos
      </p>

      <!-- Adicionar produto e editar pedido -->
      <div class="flex flex-col">
        <transition name="slide-up" mode="out-in">
          <template v-if="isEditing">
            <div class="flex justify-end">
              <p class="btn-cancel" @click="cancelEditing">Cancelar</p>
              <SaveButton @click="saveChanges" />
            </div>
          </template>
          <template v-else>
            <div class="flex justify-between space-x-1 sm:space-x-4">
              <AddButton value="Adicionar Produto" @click="openModalAddProduct" />
              <EditButton @click="startEditing" />
            </div>
          </template>
        </transition>
      </div>
    </div>

    <!-- Dados do produto -->
    <div
      v-for="product in props.order.products"
      :key="product.id" class="mt-4 md:mt-6 flex flex-col md:flex-row justify-start items-start md:items-center md:space-x-6 xl:space-x-8 w-full last:border-b-0 border-b border-gray-200"
    >
      <div class="md:flex-row flex-col flex justify-between items-start w-full pb-8 space-y-4 md:space-y-0">
        <!-- Nome e descrição -->
        <div class="w-full flex flex-col justify-start items-start space-y-8">
          <h3 class="text-xl xl:text-2xl font-semibold leading-6 text-gray-800">
            {{ product.name }}
          </h3>
          <div class="flex justify-start items-start flex-col space-y-2">
            <p class="text-sm leading-none text-gray-800">
              <span class=" text-gray-800 font-bold">Descrição:
              </span>{{
                product.description }}
            </p>
          </div>
        </div>

        <div class="flex justify-between space-x-8 items-start w-full">
          <!-- Edição -->
          <template v-if="isEditing">
            <button class="h-6 w-6" @click="decrementQuantity(product)">
              <i
                class="fa-solid fa-minus"
              />
            </button>
            <p class="text-base xl:text-lg leading-6 text-gray-800">{{ product.pivot.quantity }}</p>
            <button class="h-6 w-6" @click="incrementQuantity(product)">
              <i class="fa-solid fa-plus" />
            </button>
            <p class="text-base xl:text-lg font-semibold leading-6 text-gray-800">
              R$ {{ (product.price * product.pivot.quantity).toFixed(2) }}
            </p>
            <button
              class="font-medium text-red-600 hover:underline"
              @click="removeProduct(product)"
            >
              Remover
            </button>
          </template>

          <!-- Preço e quantidade -->
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

  <!-- Modal Adicionar Produto -->
  <AddProductModal
    :is-visible="isModalAddProductVisible" :order-id="order.id"
    :products="products" :order-products="order.products" @update:is-visible="isModalAddProductVisible = $event"
    @update:products="updateProducts"
  />
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { cloneDeep } from 'lodash'
import { useToast } from 'vue-toastification'
import { router } from '@inertiajs/vue3'
import AddProductModal from './AddProductModal.vue'
import AddButton from '@/Components/actions/AddButton.vue'
import EditButton from '@/Components/actions/EditButton.vue'
import SaveButton from '@/Components/actions/SaveButton.vue'

let props = defineProps({
    products: Array,
    order: Object,
})

const isModalAddProductVisible = ref(false)

const openModalAddProduct = () => {
    isModalAddProductVisible.value = true
}

const closeModal = () => {
    isModalAddProductVisible.value = false
}

const localOrderProducts = ref(props.order.products)

const updateProducts = (products) => {
    localOrderProducts.value = products
    closeModal()
}

const isEditing = ref(false)
const originalProducts = ref(cloneDeep(props.order.products))

const startEditing = () => {
    originalProducts.value = cloneDeep(props.order.products)
    isEditing.value = true
}

const cancelEditing = () => {
    localOrderProducts.value = cloneDeep(originalProducts.value)
    isEditing.value = false
}

const incrementQuantity = (product) => {
    product.pivot.quantity++
}

const decrementQuantity = (product) => {
    if (product.pivot.quantity > 0) {
        product.pivot.quantity--
    }
}

const toast = useToast()

const saveChanges = async () => {
    isEditing.value = false
    let success = true

    for (const product of props.order.products) {
        try {
            await axios.patch(`/orders/${props.order.id}/products/${product.id}`, {
                quantity: product.pivot.quantity,
            })
        } catch {
            success = false
            toast.error('Erro ao salvar mudanças')
        }
    }

    if (success) {
        toast.success('Produto(s) atualizado(s) com sucesso')
    }

    originalProducts.value = [...props.order.products]
}


const removeProduct = async (product) => {
    const confirmed = confirm('Você tem certeza que deseja remover este produto?')

    if (!confirmed) {
        return
    }

    try {
        await axios.delete(`/orders/${props.order.id}/products/${product.id}`)

        router.reload()
        toast.success('Produto removido com sucesso')

        localOrderProducts.value = localOrderProducts.value.filter(p => p.id !== product.id)
    } catch {
        toast.error('Erro ao remover produto')
    }
}

</script>


<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.25s ease-out;
}

.slide-up-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.slide-up-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}
</style>
