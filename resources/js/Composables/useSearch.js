import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

export function useSearch(filters) {
  let search = ref(filters.search);

  watch(search, debounce(value => {
    router.get('/clients', { search: value }, {
          preserveState: true,
          replace: true
      }
    )
  }, 500));

  return {
    search
  };
}
