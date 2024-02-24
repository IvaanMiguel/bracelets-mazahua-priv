<script setup lang="ts">
import { Customer } from '@/types/customers'

import MainLayout from '@/Layouts/MainLayout.vue'

defineOptions({
  layout: MainLayout
})

defineProps<{
  customer: Customer
}>()
</script>

<template>
  <p>{{ customer }}</p>
</template>
