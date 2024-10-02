<template>
    <Layout :title="`Editando cliente: ${originalName}`" :href="`/clients/${client.data.id}`" :active="true">

        <ClientForm :initialValues="client.data" @submit="submit" @cancel="cancel" />
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import ClientForm from '@/Components/forms/ClientForm.vue';

let props = defineProps({
    client: Object
});

let originalName = props.client.data.name;

let submit = () => { };

</script>
