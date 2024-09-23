<template>
    <form @submit.prevent="submit">
        <div class="space-y-12">
            <FormSection title="Dados do Usuário">
                <!-- Campo de Nome do Usuário -->
                <InputField wrapperClass="sm:col-span-4" name="name" id="name" label="Nome" v-model="form.name"
                    required />

                <!-- Campo de E-mail do Usuário -->
                <InputField wrapperClass="sm:col-span-4" name="email" id="email" label="E-mail" v-model="form.email"
                    required />

                <!-- Campo de Senha do Usuário -->
                <InputField wrapperClass="sm:col-span-4" type="password" name="password" id="password" label="Senha"
                    v-model="form.password" :required="!initialValues.id" />

                <div class="col-span-full">
                    <label for="profile_photo_path"
                        class="block text-sm font-medium leading-6 text-gray-900">Foto</label>
                    <div class="mt-2 flex items-center gap-x-3">
                        <img :src="imageUrl" class="h-10 w-10 flex-none rounded-full bg-gray-50" />
                        <input type="file" id="profile_photo_path" @change="handleFileChange" class="hidden" />
                        <button type="button" @click="triggerFileInput"
                            class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Mudar
                        </button>
                    </div>
                </div>
            </FormSection>

            <FormActions cancelUrl="/users" :isProcessing="form.processing" />
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';
import InputField from '@/Components/inputs/InputField.vue';
import FormSection from './FormSection.vue';
import FormActions from '../actions/FormActions.vue';

const props = defineProps({
    initialValues: {
        type: Object,
        default: () => ({
            name: '',
            email: '',
            password: '',
            profile_photo_path: '',
        })
    }
});

const emit = defineEmits(['submit']);

const form = useForm({
    ...props.initialValues,
    profile_photo_path: null
});

const toast = useToast();
const defaultImageUrl = `${window.location.origin}/images/profiles/default.png`;
const imageUrl = ref(props.initialValues.profile_photo_path ?
    `${window.location.origin}/${props.initialValues.profile_photo_path}` :
    defaultImageUrl
);

function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.profile_photo_path = file;
        imageUrl.value = URL.createObjectURL(file);
    }
}

const triggerFileInput = () => {
    document.getElementById('profile_photo_path').click();
};

const submit = async () => {


    router.post('/users', form.data(), {
        forceFormData: true,
        onSuccess: () => {
            emit('submit');
            toast.success('Usuário cadastrado com sucesso!');
        },
        onError: (errors) => {
            console.log(errors);
            let errorMessages = "Erro ao enviar o formulário.";

            if (errors.profile_photo_path) {
                errorMessages = 'A foto não pôde ser carregada. Verifique o arquivo e tente novamente.';
            }

            if (errors && !errors.profile_photo_path) {
                errorMessages = Object.values(errors).join(' ');
            }

            toast.error(errorMessages);

            if (form.data().profile_photo_path) {
                imageUrl.value = URL.createObjectURL(form.data().profile_photo_path);
            }
        }
    });

    const fileName = form.data().profile_photo_path ? form.data().profile_photo_path.name : 'default.png';
    imageUrl.value = `${window.location.origin}/storage/images/profiles/${fileName}`;
};

</script>
