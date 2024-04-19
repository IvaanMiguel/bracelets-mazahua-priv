<script setup lang="ts">
import HighlightSearch from '@/Components/HighlightSearch.vue'
import Icon from '@/Components/Icon.vue'
import { ProductListItem } from '@/types/products'
import { MdIconButton } from '@material/web/iconbutton/icon-button'
import { MdMenu } from '@material/web/menu/menu'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

defineProps<{
  product: ProductListItem
  onEdit: () => void
  onDelete: () => void
}>()

const menuButton = ref<MdIconButton>()
const menu = ref<MdMenu>()

useEventListener(menuButton, 'click', () => {
  if (!menu.value) return

  menu.value.open = !menu.value?.open
})
</script>

<template>
  <md-list-item>
    <div class="grid grid-cols-[repeat(4,minmax(0,1fr))] gap-4">
      <HighlightSearch
        class="truncate"
        :text="`${product.name}`"
        :search="`${$page.props.filters.search}`"
      />
      <HighlightSearch
        class="truncate"
        :text="`${product.category.name}`"
        :search="`${$page.props.filters.search}`"
      />
      <span class="truncate">{{ `$${product.price}` }}</span>
      <span class="truncate">{{ product.stock || 0 }}</span>
    </div>
    <div slot="end">
      <md-icon-button
        ref="menuButton"
        :id="`options-anchor-${product.id}`"
      >
        <Icon>more_vert</Icon>
      </md-icon-button>
      <md-menu
        ref="menu"
        :id="`options-menu-${product.id}`"
        :anchor="`options-anchor-${product.id}`"
        positioning="popover"
      >
        <md-menu-item @click="onEdit()">
          <div slot="headline">Editar</div>
        </md-menu-item>
        <md-menu-item @click="onDelete()">
          <div slot="headline">Eliminar</div>
        </md-menu-item>
      </md-menu>
    </div>
  </md-list-item>
</template>
