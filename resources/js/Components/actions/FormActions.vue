<template>
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <Link :href="cancelUrl" class="text-sm font-semibold leading-6 text-gray-900">
        {{ cancelLabel }}
        </Link>
        <button type="submit" :disabled="isProcessing"
            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            {{ submitLabel }}
        </button>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';

let props = defineProps({
    cancelUrl: {
        type: [String, Function],
        required: true,
    },
    cancelLabel: {
        type: String,
        default: 'Cancelar'
    },
    submitLabel: {
        type: String,
        default: 'Salvar'
    },
    isProcessing: {
        type: Boolean,
        default: false
    }
});
</script>
