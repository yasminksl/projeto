<template>

    <Head title="Clientes" />
    <Layout title="Clientes">
        <div class="flex mb-6 space-x-2">
            <SearchInput v-model="search"/>
            <CreateButton href="/clients/create" text="Cadastrar"/>
        </div>

        <ul role="list" class="divide-y divide-gray-300 border border-gray-300 rounded">
            <li class="flex justify-between gap-x-6 py-5 bg-gray-100 px-4 rounded" v-for="client in clients.data" :key="client.id">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex items-center">
                        <Link :href="`/clients/${client.id}`" class="text-sm font-semibold leading-6 text-gray-900 hover:underline">{{ client.name }}</Link>
                    </div>
                </div>
            </li>
        </ul>

        <!-- Paginator -->
        <Pagination :links="clients.meta.links" class="mt-6" />
    </Layout>

</template>

<script setup>
import { useSearch } from '@/Composables/useSearch';
import Pagination from '@/Shared/Pagination.vue';
import Layout from '@/Shared/Layout.vue';
import SearchInput from '@/Components/inputs/SearchInput.vue';
import CreateButton from '@/Components/actions/CreateButton.vue';

let props = defineProps({
    clients: Object,
    filters: Object
});

let {search} = useSearch(props.filters, '/clients');
</script>
