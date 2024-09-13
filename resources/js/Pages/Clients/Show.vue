<template>

    <Head :title="client.data.name" />
    <Layout :title="`Visualizando cliente #${client.data.id}`">
        <template #headerContent>
            <div class="flex space-x-5">
                <Link :href="`/clients/${client.data.id}`" @click="deleteClient" form="delete-form"
                    class="rounded-md bg-red-600 p-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-3">
                Deletar</Link>
                <EditButton @click="goToEditClient(client.data.id)" />
            </div>
        </template>

        <div>
            <div class="rounded bg-gray-100 py-2 px-4">
                <dl class="divide-y divide-gray-200">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-semibold leading-6 text-gray-900">Nome completo</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ client.data.name }}
                        </dd>
                    </div>

                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-semibold leading-6 text-gray-900">Endereço</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                            client.data.street_address }}, nº {{ client.data.address_number }}, {{
                                client.data.neighborhood }}, {{ client.data.city }}, CEP: {{ client.data.postal_code }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <OrderClientTable :orders="orders.data" :links="orders.meta.links" @status-change="setStatusFilter" />

        <div class="mt-5 ml-1">
            <Link href="/clients" class="text-indigo-600 hover:text-indigo-900">
            Voltar
            </Link>
        </div>
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import OrderClientTable from '@/Components/tables/OrderClientTable.vue';
import EditButton from '@/Components/actions/EditButton.vue';

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
