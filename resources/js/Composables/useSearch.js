import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

export function useSearch(searchRef, route, statusRef, filtersRef) {
    const search = ref(searchRef.search);

    watch([search, filtersRef, statusRef], debounce(([searchValue, filters, statusValue]) => {
        const combinedFilters = { ...filters, search: searchValue, status: statusValue };

        router.get(route, combinedFilters, {
            preserveState: true,
            replace: true,
            preserveScroll: true
        });
    }, 500));

    return {
        search
    };
}
