<script setup lang="ts">
import '@material/web/dialog/dialog'
import { MdDialog } from '@material/web/dialog/dialog.js'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

const props = withDefaults(defineProps<{ notCancellable?: boolean }>(), {
  notCancellable: false,
})

const dialog = ref<MdDialog | null>(null)

useEventListener(dialog, 'cancel', (e) => {
  if (props.notCancellable) e.preventDefault()
})

defineExpose({ dialog })
</script>

<template>
  <Teleport to="body">
    <md-dialog
      class="max-h-[90%]"
      v-bind="$attrs"
      ref="dialog"
    >
      <slot />
    </md-dialog>
  </Teleport>
</template>
