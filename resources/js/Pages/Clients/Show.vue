<template>

    <Head :title="client.data.name" />
    <Layout :title="`Visualizando cliente #${client.data.id}`" class="justify start space-x-5">
        <template #headerContent>
            <EditButton @click="goToEditClient(client.data.id)" />
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
</script>
