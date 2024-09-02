<template>

    <Head :title="`Pedido #${order.id}`" />
    <Layout :title="`Detalhes do pedido #${order.id}`">

        <div class="flex justify-start item-start space-y-2 flex-col">
            <p class="text-base dark:text-gray-300 font-medium leading-6 text-gray-600">
                Criado em: {{ formattedCreationDate }}
            </p>
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
            <ClientDetails :orderClient="order.client"/>
        </div>
        <CommentSection :orderComments="order.comments"/>

        <HistorySection :orderHistories="orderHistories"/>
    </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
import { format } from 'date-fns';
import { computed, ref } from 'vue';
import OrderProductList from '@/Components/sections/OrderProductList.vue';
import OrderSummary from '@/Components/sections/OrderSummary.vue';
import OrderStatus from '@/Components/sections/OrderStatus.vue';
import ClientDetails from '@/Components/sections/ClientDetails.vue';
import CommentSection from '@/Components/sections/CommentSection.vue';
import HistorySection from '@/Components/sections/HistorySection.vue';

const props = defineProps({
    order: Object,
    orderHistories: Array,
    products: Array
});

const formattedCreationDate = computed(() => {
    return format(new Date(props.order.created_at), 'dd/MM/yyyy HH:mm');
});

</script>
