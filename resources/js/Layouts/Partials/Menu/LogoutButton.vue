<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import { router } from '@inertiajs/vue3'
import '@material/web/button/text-button'
import { ref } from 'vue'
import MenuButton from './MenuButton.vue'

defineProps<{ isExpanded: boolean }>()

const processing = ref(false)
const modal = ref<InstanceType<typeof Modal>>()

const logout = () => {
  processing.value = true

  router.post(
    route('logout'),
    {},
    {
      onFinish: () => {
        processing.value = false
      },
    }
  )
}
</script>

<template>
  <MenuButton
    class="mt-auto"
    icon="logout"
    @click="modal?.dialog?.show()"
  >
    <template v-if="isExpanded">Cerrar sesión</template>
  </MenuButton>

  <Modal
    ref="modal"
    :not-cancellable="processing"
  >
    <Icon slot="icon">logout</Icon>
    <div slot="headline">Cerrar sesión</div>
    <div slot="content">¿Deseas cerrar la sesión actual?</div>
    <div slot="actions">
      <md-text-button
        @click="modal?.dialog?.close()"
        :disabled="processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="logout"
        :disabled="processing"
      >
        Aceptar
      </md-text-button>
    </div>
  </Modal>
</template>
