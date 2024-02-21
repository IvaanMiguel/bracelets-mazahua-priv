<script setup lang="ts">
import { MenuButton as MenuButtonType } from '@/types'

import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import Icon from '@/Components/Icon.vue'
import MenuButton from './MenuButton.vue'

import { ref } from 'vue'
import LogoutButton from './LogoutButton.vue'

const buttons: MenuButtonType[] = [
  {
    icon: 'home',
    label: 'Inicio',
    routeName: 'home',
    component: 'Home',
  },
  {
    icon: 'groups',
    label: 'Clientes',
    routeName: 'customers',
    component: 'Customers/Index',
  },
  {
    icon: 'inventory_2',
    label: 'Productos',
    routeName: 'products',
    component: 'Products/Index',
  },
  {
    icon: 'category',
    label: 'Categorías',
    routeName: 'categories',
    component: 'Categories/Index',
  },
  {
    icon: 'inventory',
    label: 'Pedidos',
    routeName: 'orders',
    component: 'Orders/Index',
  },
]

const localMenuExpanded = localStorage.getItem('menu-expanded') ?? 'true'
const isExpanded = ref(localMenuExpanded === 'true')

const triggerMenu = () => {
  isExpanded.value = !isExpanded.value
  localStorage.setItem('menu-expanded', `${isExpanded.value}`)
}
</script>

<template>
  <nav
    class="flex flex-col gap-3 overflow-y-auto rounded-e-lg bg-light-primary p-4 shadow-md shadow-light-shadow dark:bg-dark-primary-container dark:shadow-dark-shadow"
    :class="isExpanded ? 'w-60' : 'w-auto'"
  >
    <md-icon-button
      class="hover:md-elevation-4 active:md-elevation-1 relative left-1"
      @click="triggerMenu"
    >
      <Icon>menu</Icon>
      <md-elevation class="h-full w-full rounded-full" />
    </md-icon-button>

    <MenuButton
      v-for="button in buttons"
      :class="{
        'bg-light-on-primary !text-light-primary dark:bg-dark-on-primary-container dark:!text-dark-primary-container':
          $page.component === button.component,
      }"
      :icon="button.icon"
      :href="route(button.routeName)"
    >
      <template v-if="isExpanded">
        {{ button.label }}
      </template>
    </MenuButton>

    <LogoutButton :isExpanded="isExpanded" />
  </nav>
</template>

<style scoped>
html:not(.dark) nav {
  --md-focus-ring-color: theme(colors.light.surface-variant);
}
.dark md-icon-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.on-primary-container);
}
md-icon-button {
  --md-sys-color-on-surface-variant: theme(colors.light.on-primary);
}
</style>
