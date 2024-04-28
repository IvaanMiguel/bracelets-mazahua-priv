<script setup lang="ts">
import DisplayField from '@/Components/DisplayField.vue'
import Icon from '@/Components/Icon.vue'
import { IdCustomer } from '@/types/customers'
import { usePage } from '@inertiajs/vue3'
import { differenceInYears, format } from 'date-fns'
import { es } from 'date-fns/locale'
import { computed, ref } from 'vue'
import EditPersonalInfoForm from './EditPersonalInfoForm.vue'

const page = usePage()

const editPersonalInfoForm = ref<InstanceType<typeof EditPersonalInfoForm>>()
const currentDate = computed(() => page.props.current_date as string)
const customer = computed(() => {
  return page.props.customer as IdCustomer
})
const birthDate = computed(() => {
  return format(`${customer.value.birth_date}`, "iiii, dd 'de' MMMM 'de' y", {
    locale: es,
  })
})
const age = computed(() => {
  return differenceInYears(currentDate.value, `${customer.value.birth_date}`)
})
</script>

<template>
  <div
    class="text-on-background rounded-lg border border-light-outline-variant p-4 dark:border-dark-outline-variant"
    v-bind="$attrs"
  >
    <div class="mb-6 flex items-center justify-between">
      <h1
        class="text-xl font-medium text-light-on-background dark:text-dark-on-background"
      >
        Datos personales
      </h1>
      <md-filled-button @click="editPersonalInfoForm?.editModal?.dialog?.show">
        Editar
        <Icon slot="icon">edit</Icon>
      </md-filled-button>
    </div>
    <div class="grid gap-6 md:grid-cols-2">
      <DisplayField
        label="Nombre completo"
        :text="`${customer.name} ${customer.last_name}`"
      >
        <template #icon>
          <Icon>person</Icon>
        </template>
      </DisplayField>
      <DisplayField
        label="Número celular"
        :text="customer.phone_number"
      >
        <template #icon>
          <Icon>phone</Icon>
        </template>
      </DisplayField>
      <DisplayField
        v-if="customer.email"
        label="Email"
        :text="customer.email"
      >
        <template #icon>
          <Icon>alternate_email</Icon>
        </template>
      </DisplayField>
      <template v-if="customer.birth_date">
        <DisplayField
          label="Edad"
          :text="`${age} años`"
        >
          <template #icon>
            <Icon>elderly</Icon>
          </template>
        </DisplayField>
        <DisplayField
          label="Fecha de nacimiento"
          :text="birthDate"
        >
          <template #icon>
            <Icon>cake</Icon>
          </template>
        </DisplayField>
      </template>
    </div>
  </div>
  <EditPersonalInfoForm ref="editPersonalInfoForm" />
</template>
