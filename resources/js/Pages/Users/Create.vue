<template>

    <Head title="Criar Novo Usuário" />
    <Layout title="Novo Usuário">

        <UserForm @submit="submit" />
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import UserForm from '@/Components/forms/UserFormCreate.vue';

let submit = () => { };

</script>
