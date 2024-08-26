import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

export function useSearch(filters, route, statusRef) {
    let search = ref(filters.search);

    watch([search, statusRef], debounce(([searchValue, statusValue]) => {
        router.get(route, { search: searchValue, status: statusValue }, {
            preserveState: true,
            replace: true,
            preserveScroll: true
        });
    }, 500));

    return {
        search
    };
}
