<script setup lang="ts">
import '@material/web/textfield/outlined-text-field'

import { MdOutlinedTextField } from '@material/web/textfield/outlined-text-field'

import { ref } from 'vue'
import Icon from './Icon.vue'

withDefaults(
  defineProps<{
    emptyFloatingIcon?: boolean
    error?: string
  }>(),
  {
    emptyFloatingIcon: false,
  }
)

const model = defineModel()
const input = ref<MdOutlinedTextField | null>(null)

defineExpose({
  input,
})
</script>

<template>
  <div class="flex">
    <span
      v-if="emptyFloatingIcon"
      class="me-4 aspect-square w-6"
    ></span>
    <span
      v-else-if="$slots['floating-icon']"
      class="me-4 text-light-on-background dark:text-dark-on-background"
    >
      <slot name="floating-icon" />
    </span>
    <md-outlined-text-field
      ref="input"
      class="!w-full"
      :error="error"
      :error-text="error"
      v-bind="$attrs"
      v-model="model"
    >
      <slot />
      <Icon
        v-if="error"
        slot="trailing-icon"
      >
        error
      </Icon>
    </md-outlined-text-field>
  </div>
</template>
