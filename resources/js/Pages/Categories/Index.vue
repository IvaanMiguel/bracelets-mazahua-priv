<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import SearchBar from '@/Components/SearchBar.vue'
import Table from '@/Components/Table.vue'
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
          @click="createCategoryForm?.createModal?.dialog?.show()"
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
            :onEdit="onAction(category, editCategoryForm?.editModal?.dialog)"
            :onDelete="onAction(category, deleteCategory?.deleteModal?.dialog)"
          />
          <md-divider
            v-if="i !== categories.data.length - 1"
            class="min-h-[1px]"
            inset
          />
        </template>
      </Table>
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
