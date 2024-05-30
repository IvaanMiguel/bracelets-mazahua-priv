<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import { PopularPaymentType } from '@/types/home'

defineProps<{ popularPaymentType: PopularPaymentType }>()
</script>

<template>
  <div
    class="text-on-background flex flex-col items-center justify-center rounded-md border border-light-outline-variant p-2 text-center dark:border-dark-outline-variant"
  >
    <Icon size="3rem">payments</Icon>
    <span class="text-xs">Método de pago más utilizado</span>
    <span class="text-lg font-medium">{{ popularPaymentType.name }}</span>
  </div>
</template>
