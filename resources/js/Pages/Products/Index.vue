<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Paginator from '@/Components/Paginator.vue'
import SearchBar from '@/Components/SearchBar.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Pagination } from '@/types'
import { ProductListItem } from '@/types/products'
import '@material/web/button/filled-button'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/menu/menu'
import '@material/web/menu/menu-item'
import { ref } from 'vue'
import CreateProductForm from './Partials/CreateProductForm.vue'
import ProductItem from './Partials/ProductItem.vue'

defineOptions({ layout: MainLayout })
defineProps<{ products: Pagination<ProductListItem> }>()

const createProductForm = ref<InstanceType<typeof CreateProductForm>>()
</script>

<template>
  <div class="grid h-full grid-cols-1 overflow-y-auto p-4">
    <div
      class="md-elevation-1 relative mx-auto h-fit w-full max-w-6xl rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex items-center gap-4 p-4 pb-2">
        <h1 class="text-on-background text-2xl font-medium">
          {{ `${products.total} producto${products.total !== 1 ? 's' : ''}` }}
        </h1>

        <SearchBar
          class="ms-auto"
          placeholder="Buscar..."
          lder="Buscar por nombre..."
          :base-url="route('products')"
        />

        <md-filled-button @click="createProductForm?.createProductModal?.show">
          <Icon slot="icon">add</Icon>
          Agregar
        </md-filled-button>
      </div>

      <md-list
        v-if="products.data.length"
        class="bg-light-surface-container-lowest py-0 dark:bg-dark-surface-container"
      >
        <div
          class="text-on-background grid h-14 grid-cols-[repeat(4,minmax(0,1fr)),2.5rem] items-center gap-4 px-4 font-medium"
        >
          <span class="truncate">Nombre</span>
          <span class="truncate">Categoría</span>
          <span class="truncate">Precio</span>
          <span class="truncate">Stock</span>
        </div>
        <md-divider inset />
        <template v-for="product in products.data">
          <ProductItem
            :product
            :onEdit="() => {}"
            :onDelete="() => {}"
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
        :pagination="products"
        :selected-results="$page.props.filters.results"
        :base-url="route('products')"
      />
    </div>
  </div>

  <CreateProductForm ref="createProductForm" />
</template>
