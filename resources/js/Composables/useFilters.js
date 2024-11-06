import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export function useFilters(initialFilters, route) {
    const searchFilters = ref({ ...initialFilters })

    const applyFilters = () => {
        router.get(route, searchFilters.value, {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        })
    }

    return {
        searchFilters,
        applyFilters,
    }
}
