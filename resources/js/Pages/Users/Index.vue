<template>

    <Head title="Usuários" />
    <Layout title="Usuários">
        <template #headerContent>
            <div class="flex items-center ml-3">
                <AddButton @click="goToNewUser" value="Novo Usuário" />
            </div>
        </template>

        <div v-for="user in users.data" :key="user.id" class="flex items-center space-x-4 mb-4">
            <img :src="user.profile_photo_path" alt=""
                class="h-12 w-12 flex-none rounded-full bg-gray-50">
            <Link :href="`/users/${user.id}`">{{ user.name }}</Link>
        </div>
    </Layout>
</template>

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/inertia-vue3';
import Layout from '@/Shared/Layout.vue';
import AddButton from '@/Components/actions/AddButton.vue';

let props = defineProps({
    users: Object,
    filters: Object,
});

const goToNewUser = () => {
    router.get(`/users/create`);
};

</script>
