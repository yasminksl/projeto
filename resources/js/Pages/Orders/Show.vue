<template>
    <Head :title="order.id" />
    <Layout title="Detalhes do Pedido">
        <p>teste</p>
        <div class="mt-5 ml-1">
            <Link href="/orders" class="text-indigo-600 hover:text-indigo-900">
                Voltar
            </Link>
        </div>
    </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';

let props = defineProps({
    order: Object,
});
</script>
