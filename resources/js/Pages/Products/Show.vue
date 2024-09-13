<template>

    <Head :title="product.name" />
    <Layout title="Detalhes do Produto">
        <template #headerContent>
            <div class="flex space-x-4">
                <Link :href="`/products/${product.id}`" @click="deleteProduct" form="delete-form"
                    class="rounded-md bg-red-600 p-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-3">
                Deletar</Link>
                <EditButton @click="goToEditProduct(product.id)" />
            </div>
        </template>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ product.name }}</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Preço</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ product.price }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ product.description }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        <div class="mt-5 ml-1">
            <Link href="/products" class="text-indigo-600 hover:text-indigo-900">
            Voltar
            </Link>
        </div>
    </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import Layout from '@/Shared/Layout.vue';
import EditButton from '@/Components/actions/EditButton.vue';

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
