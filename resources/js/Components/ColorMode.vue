<script setup lang="ts">
import Icon from '@/Components/Icon.vue';
import { useDark, useToggle } from '@vueuse/core';

const isDark = useDark()
const toggleDark = useToggle(isDark)
</script>

<template>
  <md-icon-button @click="toggleDark()">
    <div
      class="absolute left-0 top-0 grid h-full w-full place-content-center overflow-hidden rounded-full"
    >
      <Transition
        mode="out-in"
        enter-from-class="translate-y-7"
        enter-active-class="transition-transform duration-200 ease-out"
        leave-active-class="transition-transform duration-200 ease-out"
        leave-to-class="-translate-y-7"
      >
        <Icon v-if="isDark">dark_mode</Icon>
        <Icon v-else>light_mode</Icon>
      </Transition>
    </div>
  </md-icon-button>
</template>
