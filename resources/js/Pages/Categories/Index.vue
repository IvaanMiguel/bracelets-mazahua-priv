<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Paginator from '@/Components/Paginator.vue'
import SearchBar from '@/Components/SearchBar.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Pagination } from '@/types'
import { CategoryListItem, IdCategory } from '@/types/categories'
import '@material/web/button/filled-button'
import { MdDialog } from '@material/web/dialog/dialog'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/menu/menu'
import '@material/web/menu/menu-item'
import { ref } from 'vue'
import CategoryItem from './Partials/CategoryItem.vue'
import CreateCategoryForm from './Partials/CreateCategoryForm.vue'
import DeleteCategory from './Partials/DeleteCategory.vue'
import EditCategoryForm from './Partials/EditCategoryForm.vue'
import Table from '@/Components/Table.vue'

defineOptions({ layout: MainLayout })
defineProps<{ categories: Pagination<CategoryListItem> }>()

const createCategoryForm = ref<InstanceType<typeof CreateCategoryForm>>()
const editCategoryForm = ref<InstanceType<typeof EditCategoryForm>>()
const deleteCategory = ref<InstanceType<typeof DeleteCategory>>()
const selectedCategory = ref<IdCategory>()

const onAction =
  (category: IdCategory, modal: MdDialog | null | undefined) => () => {
    modal!.show()
    selectedCategory.value = category
  }
</script>

<template>
  <div class="h-full p-4">
    <div
      class="md-elevation-1 relative mx-auto flex h-full w-full max-w-6xl flex-col rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex items-center gap-4 p-4 pb-2">
        <h1 class="text-on-background text-2xl font-medium">
          {{
            `${categories.total} categoría${categories.total !== 1 ? 's' : ''}`
          }}
        </h1>

        <SearchBar
          class="ms-auto w-full max-w-96"
          placeholder="Buscar por nombre..."
          :base-url="route('categories')"
        />

        <md-filled-button
          @click="createCategoryForm?.createCategoryModal?.show()"
        >
          <Icon slot="icon">add</Icon>
          Agregar
        </md-filled-button>
      </div>

      <Table
        class="flex h-full flex-col overflow-hidden"
        :headers="['Nombre', 'Productos']"
        :grid-cols="'grid-template-columns: repeat(2, minmax(0,1fr)) 2.5rem'"
        :pagination="categories"
        :url="route('categories')"
      >
        <template v-for="(category, i) in categories.data">
          <CategoryItem
            :category
            :onEdit="onAction(category, editCategoryForm?.editCategoryModal)"
            :onDelete="onAction(category, deleteCategory?.modal)"
          />
          <md-divider
            v-if="i !== categories.data.length - 1"
            class="min-h-[1px]"
            inset
          />
        </template>
      </Table>

      <!-- <div
        class="text-on-background grid h-14 grid-cols-[1fr,1fr,3.5rem] px-4 font-medium"
      >
        <span class="flex items-center">Nombre</span>
        <span class="flex items-center">Productos</span>
      </div>
      <md-list
        v-if="categories.data.length"
        class="bg-light-surface-container-lowest py-0 dark:bg-dark-surface-container"
      >
        <md-divider inset />
        <template v-for="category in categories.data">
          <CategoryItem
            :category
            :onEdit="onAction(category, editCategoryForm?.editCategoryModal)"
            :onDelete="onAction(category, deleteCategory?.modal)"
          />
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
        class="justify-end px-4 py-2"
        :pagination="categories"
        :selected-results="$page.props.filters.results"
        :base-url="route('categories')"
      /> -->
    </div>
  </div>

  <CreateCategoryForm ref="createCategoryForm" />

  <EditCategoryForm
    ref="editCategoryForm"
    :selectedCategory
  />

  <DeleteCategory
    ref="deleteCategory"
    :selectedCategory
  />
</template>
