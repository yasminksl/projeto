<template>

    <Head title="Cadastrar Novo Cliente" />
    <Layout title="Cadastrar Novo Cliente">
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Dados</h2>

                    <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- Campo de Nome Completo -->
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">
                                Nome Completo
                            </label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" autocomplete="given-name" v-model="form.name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required>
                            </div>
                        </div>

                        <div v-if="form.errors.name" v-text="`O nome é obrigatório`"
                            class="text-red-500 text-xs sm:col-start-1"></div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Endereço</h2>
                    <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- Campo de CEP -->
                        <div class="mt-3 sm:col-span-2">
                            <label for="postal_code"
                                class="block text-sm font-medium leading-6 text-gray-900">CEP</label>
                            <div class="mt-2">
                                <input type="text" name="postal_code" id="postal_code" autocomplete="postal-code"
                                    v-model="form.postal_code" @blur="fetchAddress"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required>
                            </div>
                        </div>

                        <div v-if="form.errors.postal_code" v-text="`O CEP é obrigatório`"
                            class="text-red-500 text-xs sm:col-start-1"></div>

                        <!-- Campo de Logradouro -->
                        <div class="mt-3 sm:col-span-4 sm:col-start-1">
                            <label for="street_address"
                                class="block text-sm font-medium leading-6 text-gray-900">Logradouro</label>
                            <div class="mt-2">
                                <input type="text" name="street_address" id="street_address"
                                    autocomplete="street-address" v-model="form.street_address"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required>
                            </div>
                        </div>

                        <!-- Campo de Número -->
                        <div class="mt-3 sm:col-span-1">
                            <label for="address_number"
                                class="block text-sm font-medium leading-6 text-gray-900">Número</label>
                            <div class="mt-2">
                                <input type="text" name="address_number" id="address_number"
                                    v-model="form.address_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required>
                            </div>
                        </div>

                        <div class="sm:col-span-5 sm:col-start-1">
                            <div v-if="form.errors.street_address" v-text="`O logradouro é obrigatório`"
                                class="text-red-500 text-xs"></div>
                            <div v-if="form.errors.address_number" v-text="`O número do endereço é obrigatório`"
                                class="text-red-500 text-xs"></div>
                        </div>

                        <!-- Campo de Bairro -->
                        <div class="mt-3 sm:col-span-2 sm:col-start-1">
                            <label for="neighborhood"
                                class="block text-sm font-medium leading-6 text-gray-900">Bairro</label>
                            <div class="mt-2">
                                <input type="text" name="neighborhood" id="neighborhood" v-model="form.neighborhood"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required>
                            </div>
                        </div>

                        <!-- Campo de Cidade -->
                        <div class="mt-3 sm:col-span-2">
                            <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Cidade</label>
                            <div class="mt-2">
                                <input type="text" name="city" id="city" v-model="form.city"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required>
                            </div>
                        </div>
                        <div class="sm:col-span-5 sm:col-start-1">
                            <div v-if="form.errors.neighborhood" v-text="`O bairro é obrigatório`"
                                class="text-red-500 text-xs"></div>
                            <div v-if="form.errors.city" v-text="`A cidade é obrigatória`" class="text-red-500 text-xs">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <Link href="/clients" type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancelar
                </Link>
                <button type="submit" :disabled="form.processing"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Criar</button>
            </div>
        </form>
    </Layout>
</template>

<script setup>
import axios from 'axios';
import { Link, useForm } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';

let form = useForm({
    'name': '',
    'postal_code': '',
    'street_address': '',
    'address_number': '',
    'city': '',
    'neighborhood': '',
});

let submit = () => {
    form.post('/clients');
};

let fetchAddress = async () => {
    if (form['postal_code'].length === 9 || 8) { // Validar tamanho do CEP
        try {
            let response = await axios.get(`https://viacep.com.br/ws/${form['postal_code']}/json/`);
            let data = response.data;

            if (!data.erro) {
                form['street_address'] = data.logradouro;
                form['city'] = data.localidade;
                form['neighborhood'] = data.bairro;
            } else {
                alert('CEP não encontrado.');
            }
        } catch (error) {
            console.error('Erro ao buscar o endereço:', error);
            alert('Erro ao buscar o endereço.');
        }
    } else {
        alert('CEP inválido.');
    }
};

</script>
