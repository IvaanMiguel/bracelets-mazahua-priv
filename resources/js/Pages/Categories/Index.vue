<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Paginator from '@/Components/Paginator.vue'
import SearchBar from '@/Components/SearchBar.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { useMenu } from '@/composables/useMenu'
import { Pagination } from '@/types'
import { CategoryListItem } from '@/types/categories'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/menu/menu'
import '@material/web/menu/menu-item'
import '@material/web/button/filled-button'
import { onMounted } from 'vue'

defineOptions({ layout: MainLayout })
const props = defineProps<{ categories: Pagination<CategoryListItem> }>()

  // TODO: bug al filtrar categorías. Menú no abre.
onMounted(() => {
  props.categories.data.forEach((category) => {
    useMenu(`#options-anchor-${category.id}`, `#options-menu-${category.id}`)
  })
})
</script>

<template>
  <div class="grid h-full grid-cols-1 overflow-y-auto p-4">
    <div
      class="md-elevation-1 relative mx-auto h-fit w-full max-w-6xl rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex items-center gap-4 p-4 pb-2">
        <h1 class="text-on-background text-2xl font-medium">
          {{
            `${categories.total} categoría${categories.total !== 1 ? 's' : ''}`
          }}
        </h1>

        <SearchBar
          class="ms-auto"
          placeholder="Buscar por nombre..."
          :base-url="route('categories')"
        />

        <md-filled-button>
          <Icon slot='icon'>add</Icon>
          Agregar
        </md-filled-button>
      </div>

      <Paginator
        class="justify-end p-4 pt-2"
        :pagination="categories"
        :selected-results="$page.props.filters.results"
        :base-url="route('categories')"
      />

      <md-list
        v-if="categories.data.length"
        class="bg-light-surface-container-lowest dark:bg-dark-surface-container"
      >
        <md-divider inset />
        <template v-for="category in categories.data">
          <md-list-item>
            {{ category.name }}
            <div slot="trailing-supporting-text">
              {{ `${category.stock} productos` }}
            </div>
            <div slot="end">
              <md-icon-button :id="`options-anchor-${category.id}`">
                <Icon>more_vert</Icon>
              </md-icon-button>
              <md-menu
                :id="`options-menu-${category.id}`"
                :anchor="`options-anchor-${category.id}`"
                positioning="popover"
              >
                <md-menu-item>
                  <div slot="headline">Editar</div>
                </md-menu-item>
                <md-menu-item>
                  <div slot="headline">Eliminar</div>
                </md-menu-item>
              </md-menu>
            </div>
          </md-list-item>
          <md-divider inset />
        </template>
      </md-list>
      <template v-else>
        <md-divider inset />
        <div class="p-4 text-center">
          <Icon
            class="text-on-background"
            size="3rem"
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
        <md-divider inset />
      </template>

      <Paginator
        class="justify-end p-4 pt-2"
        :pagination="categories"
        :selected-results="$page.props.filters.results"
        :base-url="route('categories')"
      />
    </div>
  </div>
</template>
