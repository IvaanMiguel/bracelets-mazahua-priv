<script setup lang="ts">
import Icon from '@/Components/Icon.vue';
import { PopularPaymentType } from '@/types/home';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage()
const popularPaymentType = ref(page.props.popularPaymentType as PopularPaymentType)
</script>

<template>
  <div
    class="flex flex-col items-center justify-center rounded-md border border-light-outline-variant p-2 text-center dark:border-dark-outline-variant text-on-background"
  >
    <Icon size="3rem">payments</Icon>
    <span class="text-xs">Método de pago más utilizado</span>
    <span class="text-lg font-medium">{{ popularPaymentType.name }}</span>
  </div>
</template>
