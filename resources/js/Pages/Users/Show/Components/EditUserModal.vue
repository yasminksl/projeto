<template>
  <transition name="fade">
    <div v-if="isVisible" class="fixed inset-0 z-50">
      <div class="fixed inset-0 bg-black opacity-50" @click="closeModal" />
      <div class="fixed inset-0 flex items-center justify-center">
        <div class="modal-content bg-white rounded shadow-lg w-11/12 md:w-1/3">
          <!-- Título -->
          <div class="border-b border-gray-200 p-4 flex justify-between">
            <div class="flex items-start space-x-3">
              <i class="fa-solid fa-book border border-gray-200 p-3 shadow-sm rounded-md" />
              <div>
                <h2 class="text-lg font-semibold">Alterar Dados</h2>
                <p class="text-sm text-gray-500">Altere os dados do usuário.</p>
              </div>
            </div>
            <i class="fa-solid fa-xmark cursor-pointer" @click="closeModal" />
          </div>

          <form @submit.prevent="submit">
            <div class="p-4">
              <!-- Nome -->
              <InputField id="name" v-model="form.name" label="Nome" required wrapper-class="mt-0" />
              <!-- E-mail -->
              <InputField
                id="email" v-model="form.email" label="E-mail" required wrapper-class="mt-0"
                class="mt-4"
              />
              <!-- Senha -->
              <InputField
                id="password" v-model="form.password" label="Senha" type="password"
                autocomplete="current-password" wrapper-class="mt-0" class="mt-4"
              />
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
import { useForm } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import { router } from '@inertiajs/vue3'
import InputField from '@/Components/inputs/InputField.vue'
import SaveButton from '@/Components/actions/SaveButton.vue'

const props = defineProps({
    isVisible: Boolean,
    user: Object,
})

const emit = defineEmits(['update:isVisible', 'submit'])

const closeModal = () => {
    emit('update:isVisible', false)
}

const form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    password: '',
})

const toast = useToast()

const submit = async () => {

    router.patch(`/users/${props.user.id}`, form.data(), {
        onSuccess: () => {
            emit('submit')
            closeModal()
            router.reload()
            toast.success('Usuário atualizado com sucesso!')
        },
        onError: (errors) => {
            let errorMessages = 'Erro ao enviar o formulário.'

            if (errors) {
                errorMessages = Object.values(errors).join(' ')
            }

            toast.error(errorMessages)
        },
    })
}

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
