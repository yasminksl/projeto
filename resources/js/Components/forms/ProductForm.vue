<template>
  <form @submit.prevent="submit">
    <div class="space-y-12 mb-5 mt-3">
      <FormSection title="Dados do Produto">
        <!-- Campo de Nome do Produto -->
        <InputField
          id="name" v-model="form.name" wrapper-class="sm:col-span-4" name="name" label="Nome"
          required
        />

        <!-- Campo de Preço do Produto -->
        <InputField
          id="price" v-model="form.price" wrapper-class="sm:col-span-4" name="price" label="Preço"
          active placeholder="0,00" required
        />

        <!-- Campo de Descrição do Produto -->
        <TextAreaField
          id="description" v-model="form.description" wrapper-class="sm:col-span-4" name="description"
          label="Descrição" required
        />
      </FormSection>

      <div class="flex justify-end">
        <SaveButton type="submit" :disabled="form.processing" />
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { useToast } from 'vue-toastification'
import { router } from '@inertiajs/vue3'
import InputField from '@/Components/inputs/InputField.vue'
import TextAreaField from '../inputs/TextAreaField.vue'
import FormSection from './FormSection.vue'
import SaveButton from '../actions/SaveButton.vue'

let props = defineProps({
    initialValues: {
        type: Object,
        default: () => ({
            name: '',
            price: '',
            description: '',
        }),
    },
    cancelUrl: String,
})

let emit = defineEmits(['submit'])

let form = useForm(props.initialValues)

const toast = useToast()

const validateForm = () => {
    const errors = {}

    if (!form.name) errors.name = 'O nome é obrigatório'
    if (!form.price) errors.postal_code = 'O preço é obrigatório'
    if (!form.description) errors.street_address = 'A descrição é obrigatória'

    return errors
}

const submit = () => {
    const errors = validateForm()

    if (Object.keys(errors).length > 0) {
        Object.values(errors).forEach(errorMessage => toast.error(errorMessage))
        return
    }

    if (props.initialValues.id) {
        router.patch(`/products/${props.initialValues.id}`, form.data(), {
            onSuccess: () => {
                emit('submit')
                toast.success('Produto atualizado com sucesso!')
            },
            onError: () => {
                toast.error('Erro ao atualizar o produto.')
            },
        })
    } else {
        router.post('/products', form.data(), {
            onSuccess: () => {
                emit('submit')
                toast.success('Produto cadastrado com sucesso!')
            },
            onError: () => {
                toast.error('Erro ao cadastrar o produto.')
            },
        })
    }
}

</script>
