<template>
  <Layout :title="`Editando cliente: ${originalName}`" :href="`/clients/${client.data.id}`" :active="true">
    <ClientForm :initial-values="client.data" @submit="submit" @cancel="cancel" />
  </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue'
import ClientForm from '@/Components/forms/ClientForm.vue'

let props = defineProps({
    client: Object,
})

let originalName = props.client.data.name

let submit = () => { }

</script>
