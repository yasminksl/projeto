<template>

    <Head title="Clientes" />
    <Layout title="Clientes">
        <div class="flex mb-6">
            <input v-model="search" type="text" placeholder="Pesquisar..." class="flex-grow border border-gray-200 rounded-lg px-2">
            <Link href="/clients/create"class="ml-3 rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Cadastrar</Link>
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
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import Pagination from '@/Shared/Pagination.vue';
import Layout from '@/Shared/Layout.vue';

let props = defineProps({
    clients: Object,
    filters: Object
});

let search = ref(props.filters.search);

watch(search, debounce(value => {
    router.get('/clients', { search: value }, {
            preserveState: true,
            replace: true
        }
    )
    }, 500)
);
</script>
