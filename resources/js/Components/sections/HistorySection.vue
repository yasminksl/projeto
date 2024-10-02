<template>
    <div class="mt-4 md:mt-6 flex flex-col w-full bg-gray-100 rounded p-3" v-if="sortedHistories.length">
        <div class="flex flex-col md:flex-row justify-start items-start md:items-center md:space-x-6 xl:space-x-8">
            <p class="text-normal md:text-normal font-semibold leading-6 xl:leading-5 text-gray-800">
                Histórico de Alterações:
            </p>
        </div>
        <div class="flex flex-col space-y-2 mt-2">
            <div v-for="history in sortedHistories" :key="history.id" class="bg-white p-2 rounded shadow">
                <p class="text-sm font-semibold text-gray-800">
                    Alterado por {{ history.changed_by }} em {{ formatDate(history.created_at) }}
                </p>
                <p class="text-sm text-gray-600">
                    {{ history.description }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useDateUtils } from '@/Composables/useUtils';

let props = defineProps({
    orderHistories: Array
});

const { formatDate } = useDateUtils();

const sortedHistories = computed(() => {
    return [...props.orderHistories].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});
</script>
