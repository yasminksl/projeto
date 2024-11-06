import axios from 'axios'
import { ref } from 'vue'

let formatCep = (postalCode) => {
    let cleanPostalCode = postalCode.replace(/\D/g, '')
    return cleanPostalCode.length === 8 ? cleanPostalCode.replace(/(\d{5})(\d{3})/, '$1-$2') : cleanPostalCode
}

export function useCep() {
    let address = ref({
        street_address: '',
        neighborhood: '',
        city: '',
    })

    let error = ref(null)

    let fetchAddress = async (postalCode) => {
        let formattedPostalCode = formatCep(postalCode)

        if (formattedPostalCode.replace(/\D/g, '').length === 8) {
            try {
                let { data } = await axios.get(`https://viacep.com.br/ws/${formattedPostalCode.replace('-', '')}/json/`)

                if (!data.erro) {
                    let formattedCep = formatCep(data.cep)

                    address.value = {
                        street_address: data.logradouro,
                        neighborhood: data.bairro,
                        city: data.localidade,
                        postal_code: formattedCep,
                    }

                    error.value = null
                } else {
                    error.value = 'CEP não encontrado.'
                }

            } catch {
                error.value = 'Erro ao buscar o endereço.'
            }
        } else {
            error.value = 'CEP inválido.'
        }
    }

    return {
        address,
        error,
        fetchAddress,
    }
}
