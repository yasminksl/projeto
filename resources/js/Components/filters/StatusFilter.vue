<template>
  <div class="flex flex-wrap items-center sm:space-x-2 space-y-2 sm:space-y-0 mr-3">
    <div
      v-for="status in statuses" :key="status.value" :class="[
        'py-2 px-4 rounded-full cursor-pointer hover:bg-gray-100',
        buttonClasses(status.value),
      ]" @click="setStatusFilter(status.value)"
    >
      <p>{{ status.label }}</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
    statuses: Array,
    selectedStatus: String,
})

const emit = defineEmits(['status-change'])

const buttonClasses = (status) => {
    const baseClasses = 'bg-gray-200 text-black'

    if (props.selectedStatus === status) {
        return 'bg-gray-800 text-white hover:bg-gray-700'
    }

    return baseClasses
}

const setStatusFilter = (status) => {
    emit('status-change', status)
}
</script>
