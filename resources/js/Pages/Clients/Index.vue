<template>

    <Head title="Clientes" />

    <!-- botão para criar clientes -->

    <ul role="list" class="divide-y divide-gray-100">
        <li class="flex justify-between gap-x-6 py-5" v-for="client in clients.data" :key="client.id">
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
    <Pagination :links="clients.links" class="mt-6" />

</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Pagination from '@/Shared/Pagination.vue';

let props = defineProps({
    clients: Object,
});
</script>
