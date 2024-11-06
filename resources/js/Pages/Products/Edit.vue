<template>
  <Layout :title="`Editando produto: ${originalName}`" :href="`/products/${product.id}`" :active="true">
    <ProductForm :product="product" :initial-values="product" @submit="submit" />
  </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue'
import ProductForm from '@/Components/forms/ProductForm.vue'

let props = defineProps({
    product: Object,
})

let originalName = props.product.name

let submit = () => { }

</script>
