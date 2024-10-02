<template>

    <Head title="Criar Novo Produto" />
    <Layout title="Novo Produto" href="/products" :active="true">
        <ProductForm @submit="submit" cancelUrl="/products" />
    </Layout>
</template>

<script setup>
import ProductForm from '@/Components/forms/ProductForm.vue';
import Layout from '@/Shared/Layout.vue';

let submit = () => { };

</script>
