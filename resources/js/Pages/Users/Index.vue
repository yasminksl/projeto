<template>

    <Head title="Usuários" />
    <Layout title="Usuários">
        <template #headerContent>
            <div class="flex items-center ml-3">
                <AddButton @click="goToNewUser" value="Novo Usuário" />
            </div>
        </template>
        <SearchInput v-model="search" class="mb-6 w-full" />

        <ul v-for="user in users.data" :key="user.id" role="list"
            class="border-b border-gray-200 bg-white hover:bg-gray-50 px-4 rounded-lg mb-6">
            <Link :href="`/users/${user.id}`">
            <li class="flex justify-between gap-x-6 py-4">
                <div class="flex items-center min-w-0 gap-x-4">
                    <img class="h-12 w-12 flex-none rounded-full bg-gray-50" :src="user.profile_photo_path" alt="">
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{
                            user.name }}
                        </p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                    <!--  -->
                </div>
            </li>
            </Link>
        </ul>
    </Layout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/inertia-vue3';
import Layout from '@/Shared/Layout.vue';
import AddButton from '@/Components/actions/AddButton.vue';
import SearchInput from '@/Components/inputs/SearchInput.vue';
import { useSearch } from '@/Composables/useSearch';

let props = defineProps({
    users: Object,
    filters: Object,
});

const { search } = useSearch(props.filters, '/users', null, null);

const goToNewUser = () => {
    router.get(`/users/create`);
};

</script>
