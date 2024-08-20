<template>

    <Head title="Criar Novo Produto" />
    <Layout title="Novo Produto">
        <ProductForm :initialValues="initialValues" @submit="submit" @cancel="cancel" />
    </Layout>
</template>

<script setup>
import ProductForm from '@/Components/ProductForm.vue';
import Layout from '@/Shared/Layout.vue';
import { useForm } from '@inertiajs/inertia-vue3';

let initialValues = {
    'name': '',
    'price': '',
    'description': '',
};

let form = useForm(initialValues);

let submit = () => {};

let cancel = () => {
window.location.href = '/products';
};

</script>
