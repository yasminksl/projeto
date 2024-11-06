<template>
  <li>
    <Link
      :href="href" :method="method" :as="as"
      class="text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-200 group"
      :class="{ 'bg-gray-800 text-white hover:bg-gray-700': active }"
    >
      <slot />
      <span class="ml-3">{{ title }}</span>
    </Link>
  </li>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    active: Boolean,
    title: String,
    href: String,
    method: String,
    as: String,
})
</script>
