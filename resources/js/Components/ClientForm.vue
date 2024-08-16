<template>
    <form @submit.prevent="submit">

        <div class="space-y-12">

            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Dados</h2>
                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Campo de Nome Completo -->
                    <InputField wrapperClass="sm:col-span-4" name="name" id="name" label="Nome Completo"
                        v-model="form.name" required />
                    <div v-if="form.errors.name" class="text-red-500 text-xs sm:col-start-1">
                        O nome é obrigatório
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Endereço</h2>
                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Campo de CEP -->
                    <InputField wrapperClass="mt-3 sm:col-span-2" name="postal_code" id="postal_code"
                        autocomplete="postal-code" label="CEP" v-model="form.postal_code" @blur="handlePostalCodeBlur"
                        required />
                    <div v-if="form.errors.postal_code" class="text-red-500 text-xs sm:col-start-1">
                        O CEP é obrigatório
                    </div>

                    <!-- Campo de Logradouro -->
                    <InputField wrapperClass="mt-3 sm:col-span-4 sm:col-start-1" name="street_address"
                        id="street_address" autocomplete="street-address" label="Logradouro"
                        v-model="form.street_address" required />

                    <!-- Campo de Número -->
                    <InputField wrapperClass="mt-3 sm:col-span-1" name="address_number" id="address_number"
                        label="Número" v-model="form.address_number" required />

                    <div class="sm:col-span-5 sm:col-start-1">
                        <div v-if="form.errors.street_address" class="text-red-500 text-xs">
                            O logradouro é obrigatório
                        </div>
                        <div v-if="form.errors.address_number" class="text-red-500 text-xs">
                            O número do endereço é obrigatório
                        </div>
                    </div>

                    <!-- Campo de Bairro -->
                    <InputField wrapperClass="mt-3 sm:col-span-2 sm:col-start-1" name="neighborhood" id="neighborhood"
                        label="Bairro" v-model="form.neighborhood" required />

                    <!-- Campo de Cidade -->
                    <InputField wrapperClass="mt-3 sm:col-span-2" name="city" id="city" label="Cidade"
                        v-model="form.city" required />

                    <div class="sm:col-span-5 sm:col-start-1">
                        <div v-if="form.errors.neighborhood" class="text-red-500 text-xs">
                            O bairro é obrigatório
                        </div>
                        <div v-if="form.errors.city" class="text-red-500 text-xs">
                            A cidade é obrigatória
                        </div>
                    </div>
                </div>
            </div>
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
import InputField from '@/Components/InputField.vue';
import { useCep } from '@/Composables/useCep';
import { useForm } from '@inertiajs/inertia-vue3';

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

let { address, error, fetchAddress } = useCep();

let handlePostalCodeBlur = () => {
    fetchAddress(form.postal_code).then(() => {
        if (!error.value) {
            form.street_address = address.value.street_address;
            form.city = address.value.city;
            form.neighborhood = address.value.neighborhood;
            form.postal_code = address.value.postal_code;
        } else {
            alert(error.value);
        }
    });
};

let submit = () => {
    if (props.initialValues.id) {
        form.patch(`/clients/${props.initialValues.id}`);
    } else {
        form.post('/clients');
    }
    emit('submit');
};
</script>
