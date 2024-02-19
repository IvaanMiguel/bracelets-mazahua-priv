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
    href: '/',
    component: 'Home',
  },
  {
    icon: 'groups',
    label: 'Clientes',
    href: '/customers',
    component: 'Customers',
  },
  {
    icon: 'inventory_2',
    label: 'Productos',
    href: '/products',
    component: 'Products',
  },
  {
    icon: 'category',
    label: 'Categorías',
    href: '/categories',
    component: 'Categories',
  },
  {
    icon: 'inventory',
    label: 'Pedidos',
    href: '/orders',
    component: 'Orders',
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
    class="flex flex-col gap-3 overflow-y-auto rounded-e-lg bg-light-primary p-4 dark:bg-dark-primary-container"
    :class="isExpanded ? 'w-60' : 'w-auto'"
  >
    <md-icon-button
      class="relative left-1"
      @click="triggerMenu"
    >
      <Icon>menu</Icon>
      <md-elevation class="h-full w-full rounded-full" />
    </md-icon-button>

    <MenuButton
      v-for="button in buttons"
      :class="[
        { '!pe-3': !isExpanded },
        {
          'bg-white !text-light-primary dark:bg-dark-on-primary-container dark:!text-dark-primary-container':
            $page.component === button.component,
        },
      ]"
      :icon="button.icon"
      :href="button.href"
    >
      <template v-if="isExpanded">
        {{ button.label }}
      </template>
    </MenuButton>

    <LogoutButton :isExpanded="isExpanded" />
  </nav>
</template>

<style scoped>
.dark md-icon-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.on-primary-container);
}
md-icon-button {
  --md-sys-color-on-surface-variant: white;
  --md-elevation-level: 0;
}
md-icon-button:hover {
  --md-elevation-level: 4;
}
md-icon-button:active {
  --md-elevation-level: 1;
}
.dark md-text-button {
  --md-sys-color-primary: theme(colors.dark.on-primary-container);
}
md-text-button {
  --md-sys-color-primary: white;
}
</style>
