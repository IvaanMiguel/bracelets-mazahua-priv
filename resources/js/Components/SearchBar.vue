<script setup lang="ts">
import '@material/web/textfield/outlined-text-field'

import { router, usePage } from '@inertiajs/vue3'

import { ref, watch } from 'vue'
import Icon from './Icon.vue'

const search = ref(usePage().props.filters.search)

const props = withDefaults(
  defineProps<{
    baseUrl?: string
  }>(),
  {
    baseUrl: route().current(),
  }
)

watch(search, (value) => {
  router.get(
    props.baseUrl,
    {
      ...route().params,
      search: value,
    },
    {
      preserveState: true,
      replace: true,
    }
  )
})
</script>

<template>
  <md-outlined-text-field
    class="w-96"
    type="search"
    v-model="search"
  >
    <Icon slot="leading-icon">search</Icon>
  </md-outlined-text-field>
</template>

<style scoped>
md-outlined-text-field {
  --md-outlined-text-field-container-shape: 1.75rem;
}
</style>
