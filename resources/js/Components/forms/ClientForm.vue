<template>
    <form @submit.prevent="submit">

        <div class="space-y-12">

            <FormSection title="Dados">
                <!-- Campo de Nome Completo -->
                <InputField wrapperClass="sm:col-span-4" name="name" id="name" label="Nome Completo" v-model="form.name"
                    required />
            </FormSection>

            <FormSection title="Endereço">
                <!-- Campo de CEP -->
                <InputField wrapperClass="mt-3 sm:col-span-2" name="postal_code" id="postal_code"
                    autocomplete="postal-code" label="CEP" v-model="form.postal_code" @blur="handlePostalCodeBlur"
                    required />

                <!-- Campo de Logradouro -->
                <InputField wrapperClass="mt-3 sm:col-span-4 sm:col-start-1" name="street_address" id="street_address"
                    autocomplete="street-address" label="Logradouro" v-model="form.street_address" required />

                <!-- Campo de Número -->
                <InputField wrapperClass="mt-3 sm:col-span-1" name="address_number" id="address_number" label="Número"
                    v-model="form.address_number" required />

                <!-- Campo de Bairro -->
                <InputField wrapperClass="mt-3 sm:col-span-2 sm:col-start-1" name="neighborhood" id="neighborhood"
                    label="Bairro" v-model="form.neighborhood" required />

                <!-- Campo de Cidade -->
                <InputField wrapperClass="mt-3 sm:col-span-2" name="city" id="city" label="Cidade" v-model="form.city"
                    required />
            </FormSection>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" @click="$emit('cancel')" class="text-sm font-semibold leading-6 text-gray-900">
                Cancelar
            </button>
            <button type="submit" :disabled="form.processing"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Salvar
            </button>
        </div>
    </form>
</template>

<script setup>
import { useCep } from '@/Composables/useCep';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';
import InputField from '@/Components/inputs/InputField.vue';
import FormSection from './FormSection.vue';

let props = defineProps({
    initialValues: {
        type: Object,
        default: () => ({
            name: '',
            postal_code: '',
            street_address: '',
            address_number: '',
            city: '',
            neighborhood: ''
        })
    }
});

let emit = defineEmits(['submit', 'cancel']);

let form = useForm(props.initialValues);

const toast = useToast();

let { address, error, fetchAddress } = useCep();

let handlePostalCodeBlur = () => {
    fetchAddress(form.postal_code).then(() => {
        if (!error.value) {
            form.street_address = address.value.street_address;
            form.city = address.value.city;
            form.neighborhood = address.value.neighborhood;
            form.postal_code = address.value.postal_code;
        } else {
            toast.error(error.value);
        }
    });
};

const validateForm = () => {
    const errors = {};

    if (!form.name) errors.name = 'O nome é obrigatório';
    if (!form.postal_code) errors.postal_code = 'O CEP é obrigatório';
    if (!form.street_address) errors.street_address = 'O logradouro é obrigatório';
    if (!form.address_number) errors.address_number = 'O número é obrigatório';
    if (!form.neighborhood) errors.neighborhood = 'O bairro é obrigatório';
    if (!form.city) errors.city = 'A cidade é obrigatória';

    return errors;
};

const submit = () => {
    const errors = validateForm();

    if (Object.keys(errors).length > 0) {
        Object.values(errors).forEach(errorMessage => toast.error(errorMessage));
        return;
    }

    if (props.initialValues.id) {
        router.patch(`/clients/${props.initialValues.id}`, form.data(), {
            onSuccess: () => {
                emit('submit');
                toast.success('Cliente atualizado com sucesso!');
            },
            onError: () => {
                toast.error("Erro ao atualizar o cliente.");
            }
        });
    } else {
        router.post('/clients', form.data(), {
            onSuccess: () => {
                emit('submit');
                toast.success('Cliente cadastrado com sucesso!');
            },
            onError: () => {
                toast.error("Erro ao cadastrar o cliente.");
            }
        });
    }
};

</script>
