<template>
    <Layout :title="`Editando cliente: ${originalName}`">

        <template #headerContent>
            <Link :href="`/clients/${client.data.id}`" @click="deleteClient" form="delete-form"
                class="rounded-md bg-red-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-3">
            Deletar</Link>
        </template>

        <ClientForm :initialValues="client.data" @submit="submit" @cancel="cancel" />
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import ClientForm from '@/Components/ClientForm.vue';
import { useToast } from 'vue-toastification';

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

let submit = () => {};

let cancel = () => {
    window.location.href = '/clients';
};

const toast = useToast();

let deleteClient = () => {
    if (confirm('Você tem certeza que deseja deletar este cliente?')) {
        form.delete(`/clients/${props.client.data.id}`);
        toast.success("Cliente deletado com sucesso!");
    }
};

</script>
