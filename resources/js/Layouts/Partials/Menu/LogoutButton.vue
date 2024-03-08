<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import { useModal } from '@/composables/useModal'
import '@material/web/button/text-button'
import MenuButton from './MenuButton.vue'

defineProps<{ isExpanded: boolean }>()

const { modal } = useModal('#logout-modal')
</script>

<template>
  <MenuButton
    class="mt-auto"
    icon="logout"
    @click="modal?.show()"
  >
    <template v-if="isExpanded">Cerrar sesión</template>
  </MenuButton>

  <Modal id="logout-modal">
    <Icon slot="icon">logout</Icon>
    <div slot="headline">Cerrar sesión</div>
    <div slot="content">¿Deseas cerrar la sesión actual?</div>
    <div slot="actions">
      <md-text-button @click="modal?.close()">Cancelar</md-text-button>
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
