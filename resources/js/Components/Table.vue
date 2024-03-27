<script setup lang="ts">
import { Pagination } from '@/types'
import '@material/web/divider/divider'
import '@material/web/list/list'
import '@material/web/list/list-item'
import { computed } from 'vue'
import Icon from './Icon.vue'
import Paginator from './Paginator.vue'

const props = withDefaults(
  defineProps<{
    noHeaders?: boolean
    headers?: string[]
    pagination: Pagination<any>
    gridCols?: string
    url?: string
  }>(),
  {
    noHeaders: false,
  }
)

const gridTemplateCols = computed(() => {
  return (
    props.gridCols ??
    `grid-template-columns: repeat(${props.headers?.length}, minmax(0, 1fr))`
  )
})
</script>

<template>
  <div>
    <md-list
      class="h-full overflow-y-auto bg-light-surface-container-lowest py-0 dark:bg-dark-surface-container"
    >
      <div
        v-if="!noHeaders"
        :style="gridTemplateCols"
        class="text-on-background sticky top-0 z-[1] grid min-h-14 items-center gap-4 border-b border-light-outline-variant bg-light-surface-container-lowest px-4 py-0 font-medium dark:border-dark-outline-variant dark:bg-dark-surface-container"
      >
        <span
          v-for="header in headers"
          class="truncate"
          :title="header"
        >
          {{ header }}
        </span>
      </div>
      <template v-if="pagination.data.length">
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
              {{
                `No se han encontrado coincidencias para "${$page.props.filters.search}".`
              }}
            </span>
          </div>
        </div>
      </template>
    </md-list>
    <div class="mt-auto">
      <md-divider />
      <Paginator
        class="justify-end px-4 py-2"
        :pagination
        :selected-results="$page.props.filters.results"
        :base-url="url"
      />
    </div>
  </div>
</template>
