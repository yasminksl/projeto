<template>
    <transition name="fade">
        <div v-if="isVisible" class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="modal-content bg-white p-6 rounded shadow-lg w-11/12 md:w-1/3">
                    <h2 class="text-lg font-semibold">Alterar Informações</h2>
                    <form @submit.prevent="submit">
                        <div class="space-y-10">
                            <ModalItem label="Nome" id="name" v-model="form.name" required />
                            <ModalItem label="E-mail" id="email" v-model="form.email" required />
                            <ModalItem label="Senha" id="password" type="password" autocomplete="current-password"
                                v-model="form.password" required />

                            <div class="mt-6 flex justify-end space-x-4">
                                <button type="button" @click="closeModal">Cancelar</button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </transition>

</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';
import ModalItem from '../utils/ModalItem.vue';

const props = defineProps({
    isVisible: Boolean,
    user: Object,
});

const emit = defineEmits(['update:isVisible', 'submit']);

const closeModal = () => {
    emit('update:isVisible', false);
};

const form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    password: '',
});

const toast = useToast();

const submit = async () => {

    router.patch(`/users/${props.user.id}`, form.data(), {
        onSuccess: () => {
            emit('submit');
            closeModal();
            router.reload();
            toast.success('Usuário atualizado com sucesso!');
        },
        onError: (errors) => {
            console.log(errors);
            console.log(form.value);
            let errorMessages = "Erro ao enviar o formulário.";

            if (errors) {
                errorMessages = Object.values(errors).join(' ');
            }

            toast.error(errorMessages);
        }
    });
};

</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.modal-content {
    max-height: 80vh;
    overflow-y: auto;
}
</style>
