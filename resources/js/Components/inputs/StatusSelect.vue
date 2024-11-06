<template>
  <div class="mb-4 relative">
    <label for="id" class="block text-sm font-medium leading-6 text-gray-900 mb-2">{{ label }}</label>

    <div
      class="btn-dropdown"
      @click="toggleDropdown"
    >
      <span v-if="selectedOption">{{ selectedOption.text }}</span>
      <span v-else class="text-gray-400">Selecione uma opção</span>
      <i class="fa-solid fa-chevron-down" />
    </div>

    <ul
      v-if="dropdownOpen"
      class="dropdown-list max-h-52"
    >
      <li
        v-for="option in options" :key="option.value" class="cursor-pointer p-2 hover:bg-gray-200"
        :style="{ backgroundColor: option.color }" @click="selectOption(option)"
      >
        {{ option.text }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
    label: { type: String, required: true },
    options: { type: Array, required: true },
    required: { type: Boolean, default: false },
    modelValue: { type: String, default: null },
})

const emit = defineEmits(['update:modelValue'])
const dropdownOpen = ref(false)
const selectedOption = ref(null)

function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value
}

function selectOption(option) {
    selectedOption.value = option
    emit('update:modelValue', option.value)
    dropdownOpen.value = false
}
</script>
