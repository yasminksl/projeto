<template>

    <Head :title="client.data.name" />
    <Layout :title="`Visualizando cliente #${client.data.id}`">
        <template #headerContent>
            <div>
                <Link href="/clients" class="text-sm mr-8">
                Voltar
                </Link>
                <Link :href="`/clients/${client.data.id}/edit`"
                    class="rounded-md bg-blue-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Editar</Link>
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
    </Layout>

</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { Link } from '@inertiajs/vue3';

let props = defineProps({
    client: Object
});

</script>
