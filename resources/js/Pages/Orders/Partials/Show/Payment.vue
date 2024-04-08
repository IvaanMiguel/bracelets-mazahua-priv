<script setup lang="ts">
import DisplayField from '@/Components/DisplayField.vue'
import Icon from '@/Components/Icon.vue'
import { Catalog } from '@/types/orders'

defineProps<{
  payment: Catalog & {
    details: string | null
  }
}>()
</script>

<template>
  <div>
    <h1 class="text-on-background mb-4 text-xl font-medium">
      Información de pago
    </h1>
    <div class="grid gap-4 md:grid-cols-2">
      <DisplayField
        label="Tipo de pago"
        :text="payment.name"
      >
        <template #icon>
          <Icon>payments</Icon>
        </template>
      </DisplayField>
      <DisplayField
        v-if="payment.details"
        label="Detalles del pago"
        :text="payment.details"
      >
        <template #icon>
          <Icon>description</Icon>
        </template>
      </DisplayField>
    </div>
  </div>
</template>
