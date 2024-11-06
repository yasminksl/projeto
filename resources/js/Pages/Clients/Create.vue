<template>
  <Head title="Cadastrar Novo Cliente" />

  <Layout title="Novo Cliente" href="/clients" :active="true">
    <ClientForm :initial-values="initialValues" @submit="submit" />
  </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue'
import ClientForm from '@/Components/forms/ClientForm.vue'

let initialValues = {
    'name': '',
    'postal_code': '',
    'street_address': '',
    'address_number': '',
    'city': '',
    'neighborhood': '',
}

let submit = () => { }

</script>
