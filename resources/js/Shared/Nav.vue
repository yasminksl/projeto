<template>
  <nav class="bg-white border-b border-gray-200 fixed z-30 w-full">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-start">
          <!-- Sidebar Mobile -->
          <button
            id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar"
            class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded"
            @click="sidebar"
          >
            <i v-if="!sidebarVisible" class="fa-solid fa-bars" />
            <i v-else class="fa-solid fa-xmark" />
          </button>

          <!-- Logo -->
          <i class="fa-solid fa-chart-pie" />
          <Link href="/" class="text-xl font-bold flex items-center ml-2.5 mr-10 text-gray-800">
            <span class="self-center whitespace-nowrap">CRM</span>
          </Link>
        </div>

        <div class="relative ml-3">
          <div>
            <!-- Imagem de Perfil -->
            <div class="flex items-center space-x-1 justify between text-sm">
              <span
                class="relative flex max-w-xs items-center rounded-full bg-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-200"
                aria-expanded="false" aria-haspopup="true"
              >
                <span class="absolute -inset-1.5" />
                <img class="h-9 w-9 rounded-full" :src="photoUrl" alt="" />
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="hidden lg:block">
    <NavBar />
  </div>
  <Transition name="slide-fade">
    <div v-if="sidebarVisible" id="sidebarBackdrop" class=" fixed inset-0 z-10">
      <NavBar />
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import NavBar from './NavBar.vue'

const { props } = usePage()

const photoUrl = props.auth?.user?.photo_url ?? null

const sidebarVisible = ref(false)

// const toggleMenu = () => {
//     menuVisible.value = !menuVisible.value;
// };

const sidebar = () => {
    sidebarVisible.value = !sidebarVisible.value
}

</script>

<style scoped>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from {
    transform: translateX(-100px);
    opacity: 0;
}

.slide-fade-leave-to {
    transform: translateX(-100px);
    opacity: 0;
}
</style>
