<template>
    <Layout :title="`Editando produto: ${originalName}`">

        <template #headerContent>
            <Link :href="`/products/${product.id}`" @click="deleteProduct" form="delete-form"
                class="rounded-md bg-red-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-3">
            Deletar</Link>
        </template>

        <ProductForm :initialValues="product" @submit="submit" @cancel="cancel" />
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import ProductForm from '@/Components/ProductForm.vue';
import { useToast } from 'vue-toastification';

let props = defineProps({
    product: Object
});

let originalName = props.product.name;

let form = useForm({
    name: originalName,
    price: props.product.price,
    description: props.product.description,
});

let submit = () => {};

let cancel = () => {
    window.location.href = `/products/${props.product.id}`;
};

const toast = useToast();

let deleteProduct = () => {
    if (confirm('Você tem certeza que deseja deletar este produto?')) {
        form.delete(`/products/${props.product.id}`);
        toast.success("Produto deletado com sucesso!");
    }
};

</script>
