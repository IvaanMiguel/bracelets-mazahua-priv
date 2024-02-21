<script setup lang="ts">
import '@material/web/tabs/tabs'
import '@material/web/tabs/primary-tab'

import { onMounted, onUnmounted, ref } from 'vue'
import { MdTabs } from '@material/web/tabs/tabs';

withDefaults(
    defineProps<{
        selectedTab: string | number
    }>(),
    {
        selectedTab: 1
    }
)

const tabs = ref<MdTabs | null>(null)
const root = ref<Document | ShadowRoot | null>(null)
const currentPanel = ref<HTMLElement | null>(null)

const tabsOnChange = (e?: Event) => {
    if (currentPanel.value) {
        currentPanel.value.hidden = true
    }

    const panelId = tabs.value?.activeTab?.getAttribute('aria-controls')

    if (root.value) {
        currentPanel.value = root.value.querySelector<HTMLElement>(`#${panelId}`)
    }

    if (currentPanel.value) {
        currentPanel.value.hidden = false
    }
}

onMounted(() => {
    root.value = tabs.value?.getRootNode() as Document | ShadowRoot

    tabs.value?.addEventListener('change', tabsOnChange);
})

onUnmounted(() => {
    tabs.value?.removeEventListener('change', tabsOnChange);
})
</script>

<template>
    <md-tabs ref="tabs">
        <slot name="tab" />
    </md-tabs>
    <slot name="panel" />
</template>
