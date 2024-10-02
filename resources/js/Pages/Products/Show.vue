<template>

    <Head :title="product.name" />
    <Layout title="Detalhes do Produto" href="/products" :active="true">
        <template #headerContent>
            <div class="flex items center ml-3">
                <DeleteButton :href="`/products/${product.id}`" @click="deleteProduct" form="delete-form"
                    </DeleteButton>
            </div>
        </template>

        <div class="rounded-lg bg-white py-2 px-4 shadow-md mb-10">
            <div class="flex items-center space-x-1">
                <p class="text-lg font-semibold leading-5 text-gray-800 py-4">{{ product.name }}</p>
                <EditButton @click="goToEditProduct(product.id)" />
            </div>
            <div>
                <dl class="divide-y divide-gray-200">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-semibold leading-6 text-gray-900">Preço</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ product.price }}</dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-semibold leading-6 text-gray-900">Descrição</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ product.description }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import Layout from '@/Shared/Layout.vue';
import EditButton from '@/Components/actions/EditButton.vue';
import DeleteButton from '@/Components/actions/DeleteButton.vue';

let props = defineProps({
    product: Object,
});

const goToEditProduct = (id) => {
    router.get(`/products/${id}/edit`);
};

const toast = useToast();

let deleteProduct = () => {
    if (confirm('Você tem certeza que deseja deletar este produto?')) {
        router.delete(`/products/${props.product.id}`, {
            onSuccess: () => {
                toast.success("Produto deletado com sucesso!");
            },
            onError: () => {
                toast.error("Erro ao deletar o produto.");
            }
        });
    }
};
</script>
