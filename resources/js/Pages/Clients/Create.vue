<template>

    <Head title="Cadastrar Novo Cliente" />

    <Layout title="Novo Cliente">

        <ClientForm :initialValues="initialValues" @submit="submit" @cancel="cancel" />
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import ClientForm from '@/Components/forms/ClientForm.vue';
import { useForm } from '@inertiajs/inertia-vue3';

let initialValues = {
    'name': '',
    'postal_code': '',
    'street_address': '',
    'address_number': '',
    'city': '',
    'neighborhood': '',
};

let form = useForm(initialValues);

let submit = () => {};

let cancel = () => {
    window.location.href = '/clients';
};

</script>
