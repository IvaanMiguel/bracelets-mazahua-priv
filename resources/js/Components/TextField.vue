<script setup lang="ts">
import '@material/web/textfield/outlined-text-field'
// import '@material/web/textfield/filled-text-field'

import Icon from './Icon.vue'

withDefaults(
  defineProps<{
    // filled?: boolean
    emptyFloatingIcon?: boolean
    error?: string
  }>(),
  {
    // filled: false,
    emptyFloatingIcon: false,
  }
)

const model = defineModel()

// const onInput = (e: InputEvent) =>
//   (model.value = (e.target as HTMLInputElement).value)
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
    <!-- :is="filled ? 'md-filled-text-field' : 'md-outlined-text-field'" -->
    <!-- @input="onInput" -->
    <md-outlined-text-field
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
