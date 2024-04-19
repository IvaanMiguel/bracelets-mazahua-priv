<script setup lang="ts">
import HighlightSearch from '@/Components/HighlightSearch.vue'
import Icon from '@/Components/Icon.vue'
import { CategoryListItem } from '@/types/categories'
import { MdIconButton } from '@material/web/iconbutton/icon-button'
import { MdMenu } from '@material/web/menu/menu'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

defineProps<{
  category: CategoryListItem
  onEdit: () => void
  onDelete: () => void
}>()

const menuButton = ref<MdIconButton>()
const menu = ref<MdMenu>()

const openMenu = () => {
  if (!menu.value) return

  menu.value.open = !menu.value?.open
}

useEventListener(menuButton, 'click', openMenu)
</script>

<template>
  <md-list-item>
    <div class="grid grid-cols-2 gap-4">
      <HighlightSearch
        class="truncate"
        :text="`${category.name}`"
        :search="`${$page.props.filters.search}`"
      />
      <span class="truncate">{{ category.stock ?? 0 }}</span>
    </div>
    <div slot="end">
      <md-icon-button
        ref="menuButton"
        :id="`options-anchor-${category.id}`"
      >
        <Icon>more_vert</Icon>
      </md-icon-button>
      <md-menu
        ref="menu"
        :id="`options-menu-${category.id}`"
        :anchor="`options-anchor-${category.id}`"
        positioning="popover"
      >
        <md-menu-item @click="onEdit()">
          <div slot="headline">Editar</div>
        </md-menu-item>
        <md-menu-item
          v-if="!category.stock"
          @click="onDelete()"
        >
          <div slot="headline">Eliminar</div>
        </md-menu-item>
      </md-menu>
    </div>
  </md-list-item>
</template>
