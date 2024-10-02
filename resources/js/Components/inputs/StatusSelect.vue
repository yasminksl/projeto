<template>
    <div class="mb-4 relative">
        <label for="id" class="block text-sm font-medium leading-6 text-gray-900 mb-2">{{ label }}</label>

        <div @click="toggleDropdown"
            class="flex justify-between items-center mt-1 cursor-pointer p-2 border-gray-300  bg-white w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0">
            <span v-if="selectedOption">{{ selectedOption.text }}</span>
            <span v-else class="text-gray-400">Selecione uma opção</span>
            <i class="fa-solid fa-chevron-down"></i>
        </div>

        <ul v-if="dropdownOpen"
            class="absolute z-10 w-full mt-1 bg-white border-gray-300 max-h-52 block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 focus:outline-none focus:ring-black focus:border-black focus:ring-offset-0">
            <li v-for="option in options" :key="option.value" @click="selectOption(option)"
                class="cursor-pointer p-2 hover:bg-gray-200" :style="{ backgroundColor: option.color }">
                {{ option.text }}
            </li>
        </ul>

    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    options: { type: Array, required: true },
    required: { type: Boolean, default: false },
    modelValue: { type: String, default: null },
});

const emit = defineEmits();
const dropdownOpen = ref(false);
const selectedOption = ref(null);

function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value;
}

function selectOption(option) {
    selectedOption.value = option;
    emit('update:modelValue', option.value); 
    dropdownOpen.value = false;
}
</script>
