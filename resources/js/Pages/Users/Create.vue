<template>
  <Head title="Criar Novo Usuário" />
  <Layout title="Novo Usuário" href="/users" :active="true">
    <UserForm @submit="submit" />
  </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue'
import UserForm from '@/Components/forms/UserForm.vue'

let submit = () => { }

</script>
