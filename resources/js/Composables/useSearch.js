import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

export function useSearch(filters, route) {
  let search = ref(filters.search);

  watch(search, debounce(value => {
    router.get(route, { search: value }, {
          preserveState: true,
          replace: true,
          preserveScroll: true
      }
    )
  }, 500));

  return {
    search
  };
}
