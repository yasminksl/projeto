<template>
  <form @submit.prevent="submit">
    <div>
      <FormSection title="Dados do Cliente">
        <!-- Campo de Nome Completo -->
        <InputField
          id="name" v-model="form.name" wrapper-class="sm:col-span-4" name="name" label="Nome Completo"
          required
        />
      </FormSection>

      <FormSection title="Endereço">
        <!-- Campo de CEP -->
        <InputField
          id="postal_code" v-model="form.postal_code" wrapper-class="sm:col-span-2" name="postal_code"
          autocomplete="postal-code" label="CEP" required @blur="handlePostalCodeBlur"
        />

        <!-- Campo de Logradouro -->
        <InputField
          id="street_address" v-model="form.street_address" wrapper-class="sm:col-span-4 sm:col-start-1"
          name="street_address" autocomplete="street-address" label="Logradouro" required
        />

        <!-- Campo de Número -->
        <InputField
          id="address_number" v-model="form.address_number" wrapper-class="sm:col-span-1" name="address_number"
          label="Número" required
        />

        <!-- Campo de Bairro -->
        <InputField
          id="neighborhood" v-model="form.neighborhood" wrapper-class="sm:col-span-2 sm:col-start-1"
          name="neighborhood" label="Bairro" required
        />

        <!-- Campo de Cidade -->
        <InputField
          id="city" v-model="form.city" wrapper-class="sm:col-span-2" name="city" label="Cidade"
          required
        />
      </FormSection>
    </div>

    <div class="flex justify-end mb-5">
      <SaveButton type="submit" :disabled="form.processing" />
    </div>
  </form>
</template>

<script setup>
import { useCep } from '@/Composables/useCep'
import { useForm } from '@inertiajs/inertia-vue3'
import { useToast } from 'vue-toastification'
import { router } from '@inertiajs/vue3'
import InputField from '@/Components/inputs/InputField.vue'
import FormSection from './FormSection.vue'
import SaveButton from '../actions/SaveButton.vue'

let props = defineProps({
    initialValues: {
        type: Object,
        default: () => ({
            name: '',
            postal_code: '',
            street_address: '',
            address_number: '',
            city: '',
            neighborhood: '',
        }),
    },
})

let emit = defineEmits(['submit', 'cancel'])

let form = useForm(props.initialValues)

const toast = useToast()

let { address, error, fetchAddress } = useCep()

let handlePostalCodeBlur = () => {
    fetchAddress(form.postal_code).then(() => {
        if (!error.value) {
            form.street_address = address.value.street_address
            form.city = address.value.city
            form.neighborhood = address.value.neighborhood
            form.postal_code = address.value.postal_code
        } else {
            toast.error(error.value)
        }
    })
}

const validateForm = () => {
    const errors = {}

    if (!form.name) errors.name = 'O nome é obrigatório'
    if (!form.postal_code) errors.postal_code = 'O CEP é obrigatório'
    if (!form.street_address) errors.street_address = 'O logradouro é obrigatório'
    if (!form.address_number) errors.address_number = 'O número é obrigatório'
    if (!form.neighborhood) errors.neighborhood = 'O bairro é obrigatório'
    if (!form.city) errors.city = 'A cidade é obrigatória'

    return errors
}

const submit = () => {
    const errors = validateForm()

    if (Object.keys(errors).length > 0) {
        Object.values(errors).forEach(errorMessage => toast.error(errorMessage))
        return
    }

    if (props.initialValues.id) {
        router.patch(`/clients/${props.initialValues.id}`, form.data(), {
            onSuccess: () => {
                emit('submit')
                toast.success('Cliente atualizado com sucesso!')
            },
            onError: () => {
                toast.error('Erro ao atualizar o cliente.')
            },
        })
    } else {
        router.post('/clients', form.data(), {
            onSuccess: () => {
                emit('submit')
                toast.success('Cliente cadastrado com sucesso!')
            },
            onError: () => {
                toast.error('Erro ao cadastrar o cliente.')
            },
        })
    }
}

</script>
