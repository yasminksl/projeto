<template>

    <div :class="wrapperClass">
        <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">
            {{ label }}
        </label>
        <div class="relative mt-2 rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3" v-if="active">
                <span class="text-gray-500 sm:text-sm">R$</span>
            </div>
            <input :type="type" :name="name" :id="id" :autocomplete="autocomplete" :placeholder="placeholder"
                :class="[inputClass, { [inputClassActive]: active }]" :required="required" v-bind="$attrs" v-on="listeners" :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)" />
        </div>
    </div>
</template>

<script setup>
import { computed, useAttrs } from 'vue';


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
            'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
    },
    inputClassActive: {
        type: String,
        default:
            'block w-full rounded-md border-0 py-1.5 pl-9 pr-20 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
    },
    modelValue: {
        type: String,
        default: '',
    },
    active: {
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
</script>
