<template>

    <Head title="Criar Novo Produto" />
    <Layout title="Novo Produto">
        <ProductForm @submit="submit" cancelUrl="/products" />
    </Layout>
</template>

<script setup>
import ProductForm from '@/Components/forms/ProductForm.vue';
import Layout from '@/Shared/Layout.vue';

let submit = () => { };

</script>
