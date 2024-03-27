<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import SearchBar from '@/Components/SearchBar.vue'
import Table from '@/Components/Table.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Pagination } from '@/types'
import { IdProduct, ProductListItem } from '@/types/products'
import '@material/web/button/filled-button'
import { MdDialog } from '@material/web/dialog/dialog'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/menu/menu'
import '@material/web/menu/menu-item'
import { ref } from 'vue'
import CreateProductForm from './Partials/CreateProductForm.vue'
import DeleteProduct from './Partials/DeleteProduct.vue'
import EditProductForm from './Partials/EditProductForm.vue'
import ProductItem from './Partials/ProductItem.vue'

defineOptions({ layout: MainLayout })
defineProps<{ products: Pagination<ProductListItem> }>()

const selectedProduct = ref<IdProduct>()
const createProductForm = ref<InstanceType<typeof CreateProductForm>>()
const editProductForm = ref<InstanceType<typeof EditProductForm>>()
const deleteProduct = ref<InstanceType<typeof DeleteProduct>>()

const onAction = (product: IdProduct, modal?: MdDialog | null) => () => {
  selectedProduct.value = product
  modal?.show()
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
          {{ `${products.total} producto${products.total !== 1 ? 's' : ''}` }}
        </h1>

        <SearchBar
          class="ms-auto w-full max-w-96"
          placeholder="Buscar..."
          lder="Buscar por nombre..."
          :base-url="route('products')"
        />

        <md-filled-button @click="createProductForm?.createProductModal?.show">
          <Icon slot="icon">add</Icon>
          Agregar
        </md-filled-button>
      </div>

      <Table
        class="flex h-full flex-col overflow-hidden"
        :headers="['Nombre', 'Categoría', 'Precio', 'Stock']"
        :grid-cols="'grid-template-columns: repeat(4, minmax(0,1fr)) 2.5rem'"
        :pagination="products"
        :url="route('products')"
      >
        <template v-for="(product, i) in products.data">
          <ProductItem
            :product
            :onEdit="onAction(product, editProductForm?.editModal!.modal)"
            :onDelete="onAction(product, deleteProduct?.deleteModal!.modal)"
          />
          <md-divider
            v-if="i !== products.data.length - 1"
            class="min-h-[1px]"
            inset
          />
        </template>
      </Table>
    </div>
  </div>

  <CreateProductForm ref="createProductForm" />

  <EditProductForm
    ref="editProductForm"
    :selectedProduct
  />

  <DeleteProduct
    ref="deleteProduct"
    :selectedProduct
  />
</template>
