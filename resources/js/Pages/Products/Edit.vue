<template>
    <Layout :title="`Editando produto: ${originalName}`">

        <ProductForm :product="product" :initialValues="product" @submit="submit" :cancelUrl="`/products/${product.id}`"/>
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import ProductForm from '@/Components/forms/ProductForm.vue';

let props = defineProps({
    product: Object
});

let originalName = props.product.name;

let submit = () => {};

</script>
