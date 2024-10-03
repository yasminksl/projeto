<template>

    <Head :title="client.data.name" />
    <Layout :title="`Visualizando cliente #${client.data.id}`" href="/clients" :active="true">
        <template #headerContent>
            <div class="flex space-x-5">
                <DeleteButton :href="`/clients/${client.data.id}`" @click="deleteClient" form="delete-form"
                    </DeleteButton>
            </div>
        </template>

        <div class="rounded-lg bg-white py-2 px-4 shadow-md">
            <div class="flex items-center space-x-1">
                <p class="text-lg font-semibold leading-5 text-gray-800 py-4">Informações</p>
                <EditButton @click="goToEditClient(client.data.id)" />
            </div>
            <dl class="divide-y divide-gray-200">
                <UserDetail label="Nome completo" :value="client.data.name" />
                <UserDetail label="Endereço"
                    :value="`${client.data.street_address}, nº ${client.data.address_number}, ${client.data.neighborhood}, ${client.data.city}, CEP: ${client.data.postal_code}`" />
            </dl>
        </div>

        <div class="mb-10">
            <OrderClientTable :orders="orders.data" :links="orders.meta.links" @status-change="setStatusFilter" />
        </div>
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import OrderClientTable from '@/Components/tables/OrderClientTable.vue';
import EditButton from '@/Components/actions/EditButton.vue';
import DeleteButton from '@/Components/actions/DeleteButton.vue';
import UserDetail from '@/Components/sections/Detail.vue';

let props = defineProps({
    client: Object,
    orders: Object,
    filters: Object,
});

const selectedStatus = ref(props.filters.status || '');

const setStatusFilter = (status) => {
    selectedStatus.value = status;
};

const goToEditClient = (id) => {
    router.get(`/clients/${id}/edit`);
};

const toast = useToast();

let deleteClient = () => {
    if (confirm('Você tem certeza que deseja deletar este cliente?')) {
        router.delete(`/clients/${props.client.data.id}`, {
            onSuccess: () => {
                toast.success("Cliente deletado com sucesso!");
            },
            onError: () => {
                toast.error("Erro ao deletar o cliente.");
            }
        });
    }
};
</script>
