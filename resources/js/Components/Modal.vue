<script setup lang="ts">
import '@material/web/dialog/dialog'
import { MdDialog } from '@material/web/dialog/dialog.js'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

const props = withDefaults(defineProps<{ notCancellable?: boolean }>(), {
  notCancellable: false,
})

const modal = ref<MdDialog | null>(null)

useEventListener(modal, 'cancel', (e) => {
  if (props.notCancellable) e.preventDefault()
})

defineExpose({ modal })
</script>

<template>
  <Teleport to="body">
    <md-dialog
      class="max-h-[90%]"
      v-bind="$attrs"
      ref="modal"
    >
      <slot />
    </md-dialog>
  </Teleport>
</template>
