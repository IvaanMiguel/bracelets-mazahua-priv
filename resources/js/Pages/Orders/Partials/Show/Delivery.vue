<script setup lang="ts">
import DisplayField from '@/Components/DisplayField.vue'
import Icon from '@/Components/Icon.vue'
import { useAddressDetails } from '@/composables/useAddressDetails'
import { IdAddress } from '@/types/customers'
import { Catalog, Delivery } from '@/types/orders'
import { format } from 'date-fns'
import { es } from 'date-fns/locale'
import { toRef } from 'vue'

const props = defineProps<{
  delivery: Delivery & {
    id: number
    delivery_app: Catalog | null
    delivery_type: Catalog
    address: IdAddress
  }
  completed: boolean
  processing?: boolean
}>()

const addressDetails = useAddressDetails(toRef(() => props.delivery.address))

const formatDate = () => {
  return format(props.delivery.date, "iiii, dd 'de' MMMM 'de' y", {
    locale: es,
  })
}

const formatTime = () => {
  const time = props.delivery.time.split(':')

  return format(new Date(0, 0, 0, +time[0], +time[1]), "'a las' HH:mm", {
    locale: es,
  })
}
</script>

<template>
  <div>
    <div class="mb-4 flex items-center justify-between gap-4">
      <h1 class="text-on-background text-xl font-medium">
        Información de entrega
      </h1>
      <md-filled-tonal-button v-if="!completed" :disabled="processing">
        Editar entrega
        <Icon slot="icon">edit</Icon>
      </md-filled-tonal-button>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
      <DisplayField
        label="Tipo de entrega"
        :text="delivery.delivery_type.name"
      >
        <template #icon>
          <Icon>quick_reorder</Icon>
        </template>
      </DisplayField>
      <DisplayField
        v-if="delivery.delivery_app"
        label="Aplicación de entrega"
        :text="delivery.delivery_app.name"
      >
        <template #icon>
          <Icon>local_shipping</Icon>
        </template>
      </DisplayField>
      <DisplayField
        label="Fecha de entrega"
        :text="formatDate()"
      >
        <template #icon>
          <Icon>event</Icon>
        </template>
      </DisplayField>
      <DisplayField
        label="Hora de entrega"
        :text="formatTime()"
      >
        <template #icon>
          <Icon>alarm</Icon>
        </template>
      </DisplayField>
      <div
        v-if="delivery.address"
        class="col-span-full"
      >
        <div class="flex gap-4">
          <Icon>explore</Icon>
          <div class="text-on-background flex flex-col gap-2">
            <span class="text-xs font-medium">Dirección de entrega</span>
            <div>
              <span class="block">{{ addressDetails.headline }}</span>
              <span
                v-if="addressDetails.supportingText"
                class="block text-sm"
              >
                {{ addressDetails.supportingText }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
