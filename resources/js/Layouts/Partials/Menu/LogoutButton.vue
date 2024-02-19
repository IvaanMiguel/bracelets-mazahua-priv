<script setup lang="ts">
import '@material/web/button/text-button'
import Modal from '@/Components/Modal.vue'
import MenuButton from './MenuButton.vue'
import Icon from '@/Components/Icon.vue'

import { ref, onMounted } from 'vue'
import { MdDialog } from '@material/web/dialog/dialog'

defineProps<{
  isExpanded: boolean
}>()

const modal = ref<MdDialog | null>(null)

const openModal = () => modal.value?.show()
const closeModal = () => modal.value?.close()

onMounted(() => {
  modal.value = document.querySelector('#logout-modal')
})
</script>

<template>
  <MenuButton
    class="mt-auto"
    :class="{ '!pe-3': !isExpanded }"
    icon="logout"
    @click="openModal"
  >
    <template v-if="isExpanded">Cerrar sesión</template>
  </MenuButton>

  <Modal id="logout-modal">
    <Icon
      slot="icon"
      class="text-light-secondary dark:text-dark-secondary"
    >
      logout
    </Icon>
    <div
      slot="headline"
      class="border-b-light-on-surface-variant"
    >
      Cerrar sesión
    </div>
    <div slot="content">¿Deseas cerrar la sesión actual?</div>
    <div slot="actions">
      <md-text-button @click="closeModal">Cancelar</md-text-button>
      <Link
        tabindex="-1"
        :href="route('logout')"
        method="POST"
        as="button"
      >
        <md-text-button>Aceptar</md-text-button>
      </Link>
    </div>
  </Modal>
</template>

<style scoped>
.dark md-text-button {
  --md-sys-color-primary: theme(colors.dark.primary);
}
md-text-button {
  --md-sys-color-primary: theme(colors.light.primary);
}
</style>
