<template>
  <Head title="Criar Novo Produto" />
  <Layout title="Novo Produto" href="/products" :active="true">
    <ProductForm cancel-url="/products" @submit="submit" />
  </Layout>
</template>

<script setup>
import ProductForm from '@/Components/forms/ProductForm.vue'
import Layout from '@/Shared/Layout.vue'

let submit = () => { }

</script>
