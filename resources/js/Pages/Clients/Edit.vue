<template>
    <Layout :title="`Editando cliente: ${originalName}`">

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

let form = useForm({
    name: originalName,
    postal_code: props.client.data.postal_code,
    street_address: props.client.data.street_address,
    address_number: props.client.data.address_number,
    city: props.client.data.city,
    neighborhood: props.client.data.neighborhood
});

let submit = () => { };

let cancel = () => {
    window.location.href = `/clients/${props.client.data.id}`;
};

</script>
