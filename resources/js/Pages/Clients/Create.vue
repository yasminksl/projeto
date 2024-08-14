<template>

    <Head title="Cadastrar Novo Cliente" />

    <form>
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
                            <input type="text" name="name" id="name" autocomplete="given-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>

                    <!-- Campo de Foto -->
                    <div class="col-span-full">
                        <label for="foto" class="block text-sm font-medium leading-6 text-gray-900">Foto</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <button type="button"
                                class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Change</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Endereço</h2>
                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <!-- Campo de CEP -->
                    <div class="mt-3 sm:col-span-2">
                        <label for="postal-code" class="block text-sm font-medium leading-6 text-gray-900">CEP</label>
                        <div class="mt-2">
                            <input type="text" name="postal-code" id="postal-code" autocomplete="postal-code" v-model="postalCode"
                                @blur="fetchAddress"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>

                    <!-- Campo de Endereço -->
                    <div class="mt-3 sm:col-span-4 sm:col-start-1">
                        <label for="street-address"
                            class="block text-sm font-medium leading-6 text-gray-900">Endereço</label>
                        <div class="mt-2">
                            <input type="text" name="street-address" id="street-address" autocomplete="street-address" v-model="streetAddress"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>

                    <!-- Campo de Número -->
                    <div class="mt-3 sm:col-span-1">
                        <label for="number" class="block text-sm font-medium leading-6 text-gray-900">Número</label>
                        <div class="mt-2">
                            <input type="text" name="number" id="number" autocomplete="number"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>

                    <!-- Campo de Bairro -->
                    <div class="mt-3 sm:col-span-2 sm:col-start-1">
                        <label for="neighborhood" class="block text-sm font-medium leading-6 text-gray-900">Bairro</label>
                        <div class="mt-2">
                            <input type="text" name="neighborhood" id="neighborhood" autocomplete="" v-model="neighborhood"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>

                    <!-- Campo de Cidade -->
                    <div class="mt-3 sm:col-span-2">
                        <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Cidade</label>
                        <div class="mt-2">
                            <input type="text" name="city" id="city" autocomplete="address-level2" v-model="city"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
            <button type="submit"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

let postalCode = ref('');
let streetAddress = ref('');
let city = ref('');
let neighborhood = ref('');

let fetchAddress = async () => {
    if (postalCode.value.length === 9 || 8) { // Validar tamanho do CEP
        try {
            let response = await axios.get(`https://viacep.com.br/ws/${postalCode.value}/json/`);
            let data = response.data;

            if (!data.erro) {
                streetAddress.value = data.logradouro;
                city.value = data.localidade;
                neighborhood.value = data.bairro;
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
