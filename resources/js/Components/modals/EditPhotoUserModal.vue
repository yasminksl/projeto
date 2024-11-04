<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white rounded shadow-lg">

                    <!-- Título -->
                    <div class="border-b border-gray-200 p-4 flex justify-between">
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-image border border-gray-200 p-3 shadow-sm rounded-md"></i>
                            <div>
                                <h2 class="text-lg font-semibold">Alterar Imagem</h2>
                                <p class="text-sm text-gray-500">Altere a imagem de perfil do usuário.</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal"></i>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="p-4">
                            <div class="flex flex-col items-center">
                                <img :src="imageUrl" alt=""
                                    class="h-48 w-48 flex-none rounded-full bg-gray-50 border border-gray-300">
                                <input type="file" id="profile_photo_path" @change="handleFileChange" class="hidden" />
                                <p class="text-sm text-gray-600 mt-2">
                                    Tipos de arquivo permitidos: .jpg, .jpeg, .png<br />
                                    Tamanho máximo: 5 MB
                                </p>
                            </div>
                            <div class="space-y-3 mt-5">
                                <div class="flex items-center space-x-2 border border-gray-200 rounded p-2 bg-gray-100 hover:bg-gray-300 cursor-pointer"
                                    @click="triggerFileInput">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    <p>Carregar Imagem</p>
                                </div>
                                <div class="flex items-center space-x-2 border border-gray-200 rounded p-2 bg-gray-100 hover:bg-gray-300 cursor-pointer"
                                    @click="removePhoto">
                                    <i class="fa-solid fa-xmark"></i>
                                    <p>Remover Imagem</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end p-4">
                            <SaveButton :disabled="form.processing" type="submit">Salvar</SaveButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';
import SaveButton from '../actions/SaveButton.vue';

const props = defineProps({
    isVisible: Boolean,
    profile_photo_path: String,
    userId: Number
});

const emit = defineEmits(['update:isVisible']);

const closeModal = () => {
    emit('update:isVisible', false);
    imageUrl.value = `/${props.profile_photo_path}`;
};

const toast = useToast();

const imageUrl = ref(`/${props.profile_photo_path}`);

const removePhoto = () => {
    imageUrl.value = `/storage/images/profiles/default.png`;
    form.value.remove_photo = true;
};

const form = ref({
    profile_photo_path: null,
    remove_photo: false,
});

function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.value.profile_photo_path = file;
        imageUrl.value = URL.createObjectURL(file);
    }
}

const triggerFileInput = () => {
    document.getElementById('profile_photo_path').click();
};

const submit = async () => {
    try {

        router.post(`/users/${props.userId}/profile-photo`, form.value, {
            forceFormData: true,
            onSuccess: () => {
                closeModal();
                router.reload();

                toast.success("Foto atualizada com sucesso!");
            },
            onError: (errors) => {

                let errorMessages = "Erro ao enviar o formulário.";

                if (errors.profile_photo_path) {
                    errorMessages = 'A foto não pôde ser carregada. Verifique o arquivo e tente novamente.';
                }

                toast.error(errorMessages);

                if (form.value.profile_photo_path) {
                    imageUrl.value = URL.createObjectURL(form.value.profile_photo_path);
                }
            }
        });
    } catch (error) {
        console.error('Erro ao salvar:', error);
        toast.error("Erro ao atualizar foto.");
    }
};

</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.modal-content {
    max-height: 80vh;
    overflow-y: auto;
}
</style>
