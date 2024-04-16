<script setup lang="ts">
import { Pagination } from '@/types'
import { usePage } from '@inertiajs/vue3'
import '@material/web/divider/divider'
import '@material/web/list/list'
import '@material/web/list/list-item'
import { computed, ref, watchEffect } from 'vue'
import Icon from './Icon.vue'
import Paginator from './Paginator.vue'

const props = withDefaults(
  defineProps<{
    noHeaders?: boolean
    headers?: string[]
    pagination?: Pagination<any>
    gridCols?: string
    url?: string
    name?: string
    noPaginator?: boolean
  }>(),
  {
    noHeaders: false,
    name: '',
    noPaginator: false
  }
)

const page = usePage()

const search = ref('')
const selectedResults = computed(() => {
  const value = page.props.filters.results
  return typeof value === 'number' ? value : value[props.name]
})
const gridTemplateCols = computed(() => {
  return (
    props.gridCols ??
    `grid-template-columns: repeat(${props.headers?.length}, minmax(0, 1fr))`
  )
})

watchEffect(() => {
  if (!page.props.filters.search) {
    search.value = ''
    return
  }

  search.value =
    typeof page.props.filters.search === 'string'
      ? page.props.filters.search
      : page.props.filters.search[props.name]
})
</script>

<template>
  <div>
    <md-list class="h-full overflow-y-auto bg-inherit p-0">
      <div
        v-if="!noHeaders"
        :style="gridTemplateCols"
        class="sticky top-0 z-[1] grid min-h-14 items-center gap-4 border-b border-light-outline-variant bg-light-surface-container-highest px-4 py-0 font-medium dark:border-dark-outline-variant dark:bg-dark-surface-container-highest"
      >
        <span
          v-for="header in headers"
          class="truncate"
          :title="header"
        >
          {{ header }}
        </span>
      </div>
      <template v-if="noPaginator || pagination?.data.length">
        <slot />
      </template>
      <template v-else>
        <div class="my-auto grid place-items-center p-4">
          <div class="text-center">
            <Icon
              class="text-light-on-background dark:text-dark-on-background"
              size="4rem"
            >
              search_off
            </Icon>
            <span
              class="block text-xl font-medium text-light-on-background dark:text-dark-on-background"
            >
              {{ `No se han encontrado coincidencias para "${search}".` }}
            </span>
          </div>
        </div>
      </template>
    </md-list>
    <div
      v-if="!noPaginator && pagination"
      class="mt-auto"
    >
      <md-divider />
      <Paginator
        class="justify-end px-4 py-2"
        :pagination
        :selected-results="selectedResults"
        :base-url="url"
        :name="name ? name : undefined"
      />
    </div>
  </div>
</template>
