<template>

    <div :class="wrapperClass">
        <label :for="id" class="flex items-center text-sm font-medium leading-6 text-gray-900" :class="class">
            {{ label }}
            <div class="ml-1 text-gray-400" v-if="aditionalLabel">
                {{ aditionalLabel }}
            </div>
        </label>
        <div class="relative mt-2 rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3" v-if="active">
                <span class="text-gray-500 sm:text-sm">R$</span>
            </div>
            <input :type="type" :name="name" :id="id" :autocomplete="autocomplete" :placeholder="placeholder"
                :class="[inputClass, { [inputClassActive]: active }]" :required="required" v-bind="$attrs"
                v-on="listeners" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
            <div class="cursor-pointer absolute inset-y-0 right-0 flex items-center pr-3" v-if="eye">
                <i :class="iconClass" @click="toggleIcon"></i>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, useAttrs, ref } from 'vue';


let props = defineProps({
    name: {
        type: String,
        required: true,
    },
    id: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'text',
    },
    label: {
        type: String,
        required: true,
    },
    autocomplete: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    wrapperClass: {
        type: String,
        default: '',
    },
    inputClass: {
        type: String,
        default:
            'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0',
    },
    inputClassActive: {
        type: String,
        default:
            'pl-9 pr-20',
    },
    modelValue: {
        type: String,
        default: '',
    },
    active: {
        type: Boolean,
        default: false,
    },
    class: String,
    aditionalLabel: String,
    eye: {
        type: Boolean,
        default: false,
    }
});

let $attrs = useAttrs();

let listeners = computed(() => {
    return {
        ...Object.fromEntries(
            Object.entries($attrs).filter(([key]) => key.startsWith('on'))
        ),
    };
});

const isEyeOpen = ref(true);

const emit = defineEmits(['eye-toggled', 'update:modelValue']);

const toggleIcon = () => {
    isEyeOpen.value = !isEyeOpen.value;
    emit('eye-toggled', isEyeOpen.value);
};

const iconClass = computed(() => {
    return isEyeOpen.value ? 'fa-solid fa-eye hover:text-gray-700' : 'fa-solid fa-eye-slash hover:text-gray-700';
});

</script>
