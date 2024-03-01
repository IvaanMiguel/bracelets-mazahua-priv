<script setup lang="ts">
import '@material/web/dialog/dialog'
import { MdDialog } from '@material/web/dialog/dialog'

import { onUnmounted, onMounted, ref } from 'vue'

const props = withDefaults(
  defineProps<{
    notCancellable?: boolean
  }>(),
  {
    notCancellable: false,
  }
)

const modal = ref<MdDialog | null>(null)

const preventCancel = (e: Event) => e.preventDefault()

onMounted(() => {
  if (props.notCancellable) {
    modal.value?.addEventListener('cancel', preventCancel)
  }
})

onUnmounted(() => {
  modal.value?.removeEventListener('cancel', preventCancel)
})
</script>

<template>
  <Teleport to="body">
    <md-dialog
      class='max-h-[85%]'
      v-bind="$attrs"
      ref="modal"
    >
      <slot />
    </md-dialog>
  </Teleport>
</template>

<style scoped>
.dark md-dialog {
  --md-dialog-container-color: theme(colors.dark.background);
  --md-dialog-headline-color: theme(colors.dark.on-background);
  --md-dialog-supporting-text-color: theme(colors.dark.on-background);
}
md-dialog {
  --md-dialog-container-color: theme(colors.light.background);
  --md-dialog-headline-color: theme(colors.light.on-background);
  --md-dialog-supporting-text-color: theme(colors.light.on-background);
}
</style>
