<template>

    <Head :title="user.data.name" />
    <Layout :title="`Visualizando usuário #${user.data.id}`">
        <template #headerContent>
            <Link :href="`/users/${user.data.id}`" @click="deleteUser" form="delete-form"
                class="rounded-md bg-red-600 p-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 mr-3">
            Deletar</Link>
        </template>

        <div class="px-4 py-5 sm:p-6 bg-gray-100 shadow sm:rounded-lg">
            <div class="px-4 sm:px-0 flex justify-between space-x-1">
                <div>
                    <h4 class="text-base font-semibold leading-7 text-gray-900">Dados</h4>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Criado em {{ formattedCreationDate }}</p>
                </div>
                <div>
                    <EditButton :active="true" @click="openEditUserModal" />
                </div>
            </div>
            <div class="mt-6 border-t border-white">
                <dl class="divide-y divide-white">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 space-x-1">
                        <div class="flex flex-col">
                            <dt class="block text-sm font-medium text-gray-900 sm:mt-px">Foto</dt>
                            <p class="max-w-2xl text-sm leading-6 text-gray-500">Clique na imagem para editá-la</p>
                        </div>

                        <div class="relative group mt-1">
                            <img :src="`/${user.data.profile_photo_path}`" alt=""
                                class="h-48 w-48 flex-none rounded-full bg-gray-50">

                            <div @click="openEditPhotoUserModal"
                                class="absolute top-0 left-0 h-48 w-48 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536M9 11l6.268-6.268a2 2 0 012.828 0l3.536 3.536a2 2 0 010 2.828L15 17l-6 1.5L9 11z" />
                                </svg>
                            </div>
                        </div>

                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="block text-sm font-medium text-gray-900 sm:mt-px">Nome</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ user.data.name }}</dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="block text-sm font-medium text-gray-900 sm:mt-px">E-mail</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ user.data.email }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>


        <div class="mt-5 ml-1">
            <Link href="/users" class="text-indigo-600 hover:text-indigo-900">
            Voltar
            </Link>
        </div>

        <EditPhotoUserModal :isVisible="isModalEditPhotoUserVisible"
            @update:isVisible="isModalEditPhotoUserVisible = $event" :profile_photo_path="user.data.profile_photo_path"
            :userId="user.data.id" />

        <EditUserModal :isVisible="isModalEditUserVisible" @update:isVisible="isModalEditUserVisible = $event"
            :user="user.data" />
    </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { format } from 'date-fns';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import EditButton from '@/Components/actions/EditButton.vue';
import EditPhotoUserModal from '@/Components/modals/EditPhotoUserModal.vue';
import EditUserModal from '@/Components/modals/EditUserModal.vue';

let props = defineProps({
    user: Object,
});

const formattedCreationDate = computed(() => {
    return format(new Date(props.user.data.created_at), "dd/MM/yyyy 'às' HH:mm");
});

const isModalEditPhotoUserVisible = ref(false);

const openEditPhotoUserModal = () => {
    isModalEditPhotoUserVisible.value = true;
};

const isModalEditUserVisible = ref(false);

const openEditUserModal = () => {
    isModalEditUserVisible.value = true;
};

const toast = useToast();

let deleteUser = () => {
    if (confirm('Você tem certeza que deseja deletar este usuário?')) {
        router.delete(`/users/${props.user.data.id}`, {
            onSuccess: () => {
                toast.success("Usuário deletado com sucesso!");
            },
            onError: () => {
                toast.error("Erro ao deletar o usuário.");
            }
        });
    }
};
</script>
