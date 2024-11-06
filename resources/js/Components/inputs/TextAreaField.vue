<template>
  <div :class="wrapperClass">
    <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">
      {{ label }}
    </label>
    <div class="relative mt-2 rounded-md shadow-sm">
      <textarea
        :id="id" :name="name" :autocomplete="autocomplete" :placeholder="placeholder"
        :class="textAreaClass" v-bind="$attrs" :value="modelValue" :rows="rows"
        :cols="cols" v-on="listeners" @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>
  </div>
</template>

<script setup>
import { computed, useAttrs } from 'vue'

defineProps({
    name: {
        type: String,
        required: true,
    },
    id: {
        type: String,
        required: true,
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
    wrapperClass: {
        type: String,
        default: '',
    },
    textAreaClass: {
        type: String,
        default:
            'block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 h-32',
    },
    modelValue: {
        type: String,
        default: '',
    },
    rows: {
        type: Number,
        default: 4,
    },
    cols: {
        type: Number,
        default: 50,
    },
})

defineEmits(['update:modelValue'])

let $attrs = useAttrs()

let listeners = computed(() => {
    return {
        ...Object.fromEntries(
            Object.entries($attrs).filter(([key]) => key.startsWith('on')),
        ),
    }
})
</script>
