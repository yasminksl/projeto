<template>
    <form @submit.prevent="submit">

        <div class="space-y-12">
            <FormSection title="Dados do Produto">
                <!-- Campo de Nome do Produto -->
                <InputField wrapperClass="sm:col-span-4" name="name" id="name" label="Nome" v-model="form.name"
                    required />

                <!-- Campo de Preço do Produto -->
                <InputField wrapperClass="sm:col-span-4" name="price" id="price" label="Preço" v-model="form.price"
                    active placeholder="0,00" required />

                <!-- Campo de Descrição do Produto -->
                <TextAreaField wrapperClass="sm:col-span-4" name="description" id="description" label="Descrição"
                    v-model="form.description" required />

            </FormSection>

            <FormActions cancelUrl="/products" :isProcessing="form.processing" />
        </div>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'vue-toastification';
import InputField from '@/Components/inputs/InputField.vue';
import TextAreaField from '../inputs/TextAreaField.vue';
import FormSection from './FormSection.vue';
import FormActions from '../actions/FormActions.vue';

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

const validateForm = () => {
    const errors = {};

    if (!form.name) errors.name = 'O nome é obrigatório';
    if (!form.price) errors.postal_code = 'O preço é obrigatório';
    if (!form.description) errors.street_address = 'A descrição é obrigatória';

    return errors;
};

const submit = () => {
    const errors = validateForm();

    if (Object.keys(errors).length > 0) {
        Object.values(errors).forEach(errorMessage => toast.error(errorMessage));
        return;
    }

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
