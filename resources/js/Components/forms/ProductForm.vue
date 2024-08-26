<template>
    <form @submit.prevent="submit">

        <div class="space-y-12">

            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Dados do Produto</h2>
                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Campo de Nome do Produto -->
                    <InputField wrapperClass="sm:col-span-4" name="name" id="name" label="Nome" v-model="form.name"
                        required />
                    <div v-if="form.errors.name" class="text-red-500 text-xs sm:col-start-1">
                        O nome é obrigatório
                    </div>

                    <!-- Campo de Preço do Produto -->
                    <InputField wrapperClass="sm:col-span-4" name="price" id="price" label="Preço" v-model="form.price" active placeholder="0,00"
                        required />
                    <div v-if="form.errors.price" class="text-red-500 text-xs sm:col-start-1">
                        O preço é obrigatório
                    </div>

                    <!-- Campo de Descrição do Produto -->
                    <TextAreaField wrapperClass="sm:col-span-4" name="description" id="description" label="Descrição"
                        v-model="form.description" required />
                    <div v-if="form.errors.description" class="text-red-500 text-xs sm:col-start-1">
                        A descrição é obrigatória
                    </div>

                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <Link href="/products" class="text-sm font-semibold leading-6 text-gray-900">
                    Cancelar
                </Link>
                <button type="submit" :disabled="form.processing"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import InputField from '@/Components/inputs/InputField.vue';
import TextAreaField from '../inputs/TextAreaField.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'vue-toastification';

let props = defineProps({
    initialValues: {
        type: Object,
        default: () => ({
            name: '',
            price: '',
            description: '',
        })
    }
});

let emit = defineEmits(['submit']);

let form = useForm(props.initialValues);

const toast = useToast();

let submit = () => {
    if (props.initialValues.id) {
        form.patch(`/products/${props.initialValues.id}`);
        toast.success("Produto atualizado com sucesso!");
    } else {
        form.post('/products');
        toast.success("Produto cadastrado com sucesso!");
    }
    emit('submit');
};
</script>
