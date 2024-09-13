<template>

    <Head :title="`Pedido #${order.id}`" />
    <Layout :title="`Detalhes do pedido #${order.id}`">

        <div class="flex justify-between items-center">
            <div>
                <p class="text-base font-medium leading-6 text-gray-600">
                    Criado em: {{ formattedCreationDate }}
                </p>
            </div>

            <div>
                <Link href="#" @click.prevent="deleteOrder"
                    class="rounded-md bg-red-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-3">
                Deletar</Link>
            </div>
        </div>

        <div class="mt-5 ml-1">
            <Link href="/orders" class="text-indigo-600 hover:text-indigo-900">
            Voltar
            </Link>
        </div>
        <div
            class="mt-10 flex flex-col xl:flex-row justify-center items-stretch w-full xl:space-x-8 space-y-4 md:space-y-6 xl:space-y-0">
            <div class="flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8">
                <OrderProductList :products="products" :order="order" />
                <div
                    class="flex justify-center flex-col md:flex-row items-stretch w-full space-y-4 md:space-y-0 md:space-x-6 xl:space-x-8">
                    <OrderSummary :order="order" />
                    <OrderStatus :order="order" />
                </div>
            </div>
            <ClientDetails :orderId="order.id" :orderClient="order.client" :clients="clients.data" />
        </div>
        <CommentSection :orderComments="order.comments" />

        <HistorySection :orderHistories="orderHistories" />
    </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
import { format } from 'date-fns';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import OrderProductList from '@/Components/sections/OrderProductList.vue';
import OrderSummary from '@/Components/sections/OrderSummary.vue';
import OrderStatus from '@/Components/sections/OrderStatus.vue';
import ClientDetails from '@/Components/sections/ClientDetails.vue';
import CommentSection from '@/Components/sections/CommentSection.vue';
import HistorySection from '@/Components/sections/HistorySection.vue';

const props = defineProps({
    order: Object,
    orderHistories: Array,
    products: Array,
    clients: Object
});

const formattedCreationDate = computed(() => {
    return format(new Date(props.order.created_at), "dd/MM/yyyy 'às' HH:mm");
});

const toast = useToast();

const deleteOrder = () => {
    if (confirm('Você tem certeza que deseja deletar este pedido?')) {
        router.delete(`/orders/${props.order.id}`, {
            onSuccess: () => {
                toast.success('Pedido deletado com sucesso!');
            },
            onError: () => {
                toast.error('Ocorreu um erro ao tentar deletar o pedido.');
            }
        });
    }
};

</script>
