<script setup lang="ts">
import { Pagination } from '@/types'
import { router } from '@inertiajs/vue3'
import '@material/web/iconbutton/icon-button'
import '@material/web/select/outlined-select'
import { MdOutlinedSelect } from '@material/web/select/outlined-select'
import '@material/web/select/select-option'
import { ref, watch } from 'vue'
import Icon from './Icon.vue'

const props = withDefaults(
  defineProps<{
    selectedResults?: number | null
    pagination: Pagination<any>
    rowsPerPage?: number[]
    baseUrl?: string
  }>(),
  {
    rowsPerPage: () => [15, 30, 50],
    baseUrl: route().current(),
  }
)

const results = ref<number | null>(null)
const selectOption = (i: number) => {
  if (
    props.selectedResults &&
    props.rowsPerPage.includes(props.selectedResults)
  ) {
    return props.selectedResults === props.rowsPerPage[i]
  }

  return i === 0
}

watch(results, (value) => {
  router.get(
    props.baseUrl,
    {
      ...route().params,
      results: value,
    },
    {
      preserveState: true,
      replace: true,
    }
  )
})
</script>

<template>
  <div class="flex items-center gap-1 sm:gap-7">
    <div class="text-center">
      <span
        class="me-2 text-sm text-light-on-background dark:text-dark-on-background"
      >
        Resultados por página:
      </span>
      <md-outlined-select
        class="min-w-fit"
        menu-positioning="absolute"
        v-model="results"
      >
        <md-select-option
          v-for="(rowsPerPageOption, i) in rowsPerPage"
          :value="rowsPerPageOption"
          :selected="selectOption(i)"
        >
          <div slot="headline">{{ rowsPerPageOption }}</div>
        </md-select-option>
      </md-outlined-select>
    </div>
    <div
      class="hidden text-sm text-light-on-background dark:text-dark-on-background sm:block"
    >
      <span class="text-nowrap">{{ pagination.from }}-{{ pagination.to }}</span>
      <span class="text-nowrap"> de {{ pagination.total }}</span>
    </div>
    <div class="flex gap-1 sm:gap-2">
      <Link
        as="button"
        :href="pagination.first_page_url"
        :disabled="pagination.current_page === 1"
        title="Primera página"
        tabindex="-1"
        preserve-scroll
        preserve-state
      >
        <md-icon-button :disabled="pagination.current_page === 1">
          <Icon>first_page</Icon>
        </md-icon-button>
      </Link>
      <Link
        as="button"
        :href="pagination.prev_page_url ?? ''"
        :disabled="pagination.prev_page_url === null"
        title="Página anterior"
        tabindex="-1"
        preserve-scroll
        preserve-state
      >
        <md-icon-button :disabled="pagination.prev_page_url === null">
          <Icon>navigate_before</Icon>
        </md-icon-button>
      </Link>
      <Link
        as="button"
        :href="pagination.next_page_url ?? ''"
        :disabled="pagination.next_page_url === null"
        title="Página siguiente"
        tabindex="-1"
        preserve-scroll
        preserve-state
      >
        <md-icon-button :disabled="pagination.next_page_url === null">
          <Icon>navigate_next</Icon>
        </md-icon-button>
      </Link>
      <Link
        as="button"
        :href="pagination.last_page_url"
        :disabled="pagination.current_page === pagination.last_page"
        title="Última página"
        tabindex="-1"
        preserve-scroll
        preserve-state
      >
        <md-icon-button
          :disabled="pagination.current_page === pagination.last_page"
        >
          <Icon>last_page</Icon>
        </md-icon-button>
      </Link>
    </div>
  </div>
</template>

<style scoped>
md-outlined-select::part(field) {
  --_top-space: 0.5rem;
  --_bottom-space: 0.5rem;
}
md-select-option {
  --md-menu-item-one-line-container-height: 2.5rem;
  --md-menu-item-top-space: 0.25rem;
  --md-menu-item-bottom-space: 0.25rem;
}
</style>
