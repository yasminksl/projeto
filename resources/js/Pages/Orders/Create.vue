<template>

    <Head title="Criar Novo Pedido" />
    <Layout title="Novo Pedido">
        <OrderForm :clients="props.clients.data" :products="props.products" :initialValues="initialValues"
            @submit="submit" />
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import OrderForm from '@/Components/forms/OrderForm.vue';
import { useForm } from '@inertiajs/inertia-vue3';

let props = defineProps({
    clients: Object,
    products: Array
})

let initialValues = {
    'client_id': '',
    'total_amount': '',
    'status': '',
    'scheduled_delivery_date': '',
    'comments': '',
    'products': [],
    'discount': '',
    'interest': ''
};

let form = useForm(initialValues);

let submit = () => { };

</script>
