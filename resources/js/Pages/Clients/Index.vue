<template>

    <Head title="Clientes" />

    <!-- botão para criar clientes -->

    <div class="mb-6">
        <input v-model="search" type="text" placeholder="Search..." class="border border-gray-200 rounded-lg px-2">
    </div>

    <ul role="list" class="divide-y divide-gray-100">
        <li class="flex justify-between gap-x-6 py-5" v-for="client in clients.data" :key="client.id">
            <!-- colocar a div em um link para mostrar o perfil do cliente -->
            <div class="flex min-w-0 gap-x-4">
                <img class="h-12 w-12 flex-none rounded-full bg-gray-50"
                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                    alt="">
                <div class="min-w-0 flex items-center">
                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ client.name }}</p>
                </div>
            </div>
        </li>
    </ul>

    <!-- Paginator -->
    <Pagination :links="clients.meta.links" class="mt-6" />

</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import Pagination from '@/Shared/Pagination.vue';

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
