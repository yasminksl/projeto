<template>
  <Head :title="user.data.name" />
  <Layout :title="`Visualizando usuário #${user.data.id}`" href="/users" :active="true">
    <template #headerContent>
      <DeleteButton :href="`/users/${user.data.id}`" form="delete-form" @click="deleteUser" />
    </template>

    <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg mb-6">
      <!-- Título -->
      <div class="flex mb-4 items-center space-x-1">
        <h4 class="text-lg font-semibold leading-5 text-gray-800">Dados</h4>
        <EditButton @click="openEditUserModal" />
      </div>

      <div class="border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
          <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 space-x-1">
            <!-- Foto -->
            <div class="flex flex-col">
              <dt class="block text-sm font-medium text-gray-900 sm:mt-px">Foto</dt>
              <p class="max-w-2xl text-sm leading-6 text-gray-500">Clique na imagem para editá-la</p>
            </div>

            <!-- Editar Foto -->
            <div class="relative group mt-1">
              <img
                :src="user.data.profile_photo_url" alt=""
                class="h-48 w-48 flex-none rounded-full bg-gray-50"
              />

              <div
                class="absolute top-0 left-0 h-48 w-48 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                @click="openEditPhotoUserModal"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                >
                  <path
                    stroke-linecap="round" stroke-linejoin="round"
                    d="M15.232 5.232l3.536 3.536M9 11l6.268-6.268a2 2 0 012.828 0l3.536 3.536a2 2 0 010 2.828L15 17l-6 1.5L9 11z"
                  />
                </svg>
              </div>
            </div>
          </div>

          <!-- Nome -->
          <UserDetail label="Nome" :value="user.data.name" />

          <!-- E-mail -->
          <UserDetail label="E-mail" :value="user.data.email" />

          <!-- Data de Criação -->
          <UserDetail label="Data de Criação" :value="formattedCreationDate" />
        </dl>
      </div>
    </div>

    <EditPhotoUserModal
      :is-visible="isModalEditPhotoUserVisible"
      :profile-photo-path="user.data.profile_photo_path" :profile-photo-url="user.data.profile_photo_url" :user-id="user.data.id"
      @update:is-visible="isModalEditPhotoUserVisible = $event"
    />

    <EditUserModal
      :is-visible="isModalEditUserVisible" :user="user.data"
      @update:is-visible="isModalEditUserVisible = $event"
    />
  </Layout>
</template>

<script setup>
import Layout from '@/Shared/Layout.vue'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { format } from 'date-fns'
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import EditButton from '@/Components/actions/EditButton.vue'
import EditPhotoUserModal from '@/Pages/Users/Show/Components/EditPhotoUserModal.vue'
import EditUserModal from '@/Pages/Users/Show/Components/EditUserModal.vue'
import DeleteButton from '@/Components/actions/DeleteButton.vue'
import UserDetail from '@/Components/sections/Detail.vue'

let props = defineProps({
    user: Object,
})

const formattedCreationDate = computed(() => {
    return format(new Date(props.user.data.created_at), 'dd/MM/yyyy \'às\' HH:mm')
})

const isModalEditPhotoUserVisible = ref(false)

const openEditPhotoUserModal = () => {
    isModalEditPhotoUserVisible.value = true
}

const isModalEditUserVisible = ref(false)

const openEditUserModal = () => {
    isModalEditUserVisible.value = true
}

const toast = useToast()

let deleteUser = () => {
    if (confirm('Você tem certeza que deseja deletar este usuário?')) {
        router.delete(`/users/${props.user.data.id}`, {
            onSuccess: () => {
                toast.success('Usuário deletado com sucesso!')
            },
            onError: () => {
                toast.error('Erro ao deletar o usuário.')
            },
        })
    }
}
</script>
