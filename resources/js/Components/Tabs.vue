<script setup lang="ts">
import '@material/web/tabs/primary-tab'
import '@material/web/tabs/tabs'
import { MdTabs } from '@material/web/tabs/tabs'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const tabs = ref<MdTabs | null>(null)
const tabsCount = ref<number | undefined>(0)
const tabTranslation = ref<string | null>(null)

useEventListener(tabs, 'change', () => {
  tabTranslation.value = `${-tabs.value!.activeTabIndex * 100}%`
})

onMounted(() => {
  tabsCount.value = tabs.value?.childElementCount
})
</script>

<template>
  <div class="flex h-full flex-col">
    <md-tabs
      ref="tabs"
      v-bind="$attrs"
    >
      <slot name="tab" />
    </md-tabs>
    <div
      id="panel-container"
      class="grid flex-1 overflow-x-hidden"
    >
      <slot name="panel" />
    </div>
  </div>
</template>

<style scoped>
#panel-container {
  grid-template-columns: repeat(v-bind(tabsCount), 100%);
}
:slotted([role='tabpanel']) {
  transform: translateX(v-bind(tabTranslation));
  transition: transform 0.2s ease-in-out 0s;
}
</style>
