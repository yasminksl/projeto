<template>
    <div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8 bg-gray-200">
        <div class="bg-white sm:mx-auto sm:w-full sm:max-w-sm p-10 shadow-lg rounded-lg">

            <div class="text-3xl text-gray-800 flex flex-col space-y-14 items-center mt-10">
                <i class="fa-solid fa-chart-pie fa-2xl"></i>
                <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-800">Entre na sua
                    conta
                </h2>
            </div>

            <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- E-mail -->
                    <InputField name="email" id="email" type="email"
                        autocomplete="email" placeholder="E-mail" v-model="form.email" required />

                    <InputField name="password" id="password"
                        :type="isEyeOpen ? 'text' : 'password'" autocomplete="current-password" placeholder="Senha"
                        v-model="form.password" required eye @eye-toggled="handleEyeToggle" />

                    <div>
                        <button type="submit"
                            class=" mt-10 flex w-full justify-center rounded-md bg-gray-800 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-800"
                            :disabled="form.processing">
                            Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import InputField from '@/Components/inputs/InputField.vue';

const form = useForm({
    'email': '',
    'password': '',
});

const toast = useToast();

const errorMessages = ref('');

const isEyeOpen = ref(false);

const handleEyeToggle = (isOpen) => {
    isEyeOpen.value = !isOpen;
    console.log('O olho está agora:', isOpen ? 'Aberto' : 'Fechado');
};

const submit = async () => {

    router.post('/login', form.data(), {
        onSuccess: () => {
            emit('submit');
        },
        onError: (errors) => {
            if (errors) {
                errorMessages.value = Object.values(errors).join(' ');
            }

            toast.error(errorMessages.value);
        }
    });
};

</script>
