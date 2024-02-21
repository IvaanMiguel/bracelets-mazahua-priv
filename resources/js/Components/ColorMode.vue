<script setup lang="ts">
import Icon from '@/Components/Icon.vue'

import { computed } from 'vue'
import { useDark, useToggle } from '@vueuse/core'

const isDark = useDark()
const toggleDark = useToggle(isDark)

const colorModeIcon = computed(() =>
  isDark.value ? 'light_mode' : 'dark_mode'
)
</script>

<template>
  <md-icon-button @click="toggleDark()">
    <Icon>{{ colorModeIcon }}</Icon>
  </md-icon-button>
</template>
