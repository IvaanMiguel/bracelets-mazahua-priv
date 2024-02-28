<script setup lang="ts">
import { Address } from '@/types/customers'

import { InertiaForm } from '@inertiajs/vue3'

import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import TextField from '@/Components/TextField.vue'

defineProps<{
  submit: (e?: Event) => void
  form: InertiaForm<Address>
}>()
</script>

<template>
  <h1
    class="mb-4 text-xl font-medium text-light-on-background dark:text-dark-on-background"
  >
    Direcciones
  </h1>
  <Form
    class="flex flex-col"
    :submit="submit"
  >
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <TextField
        class="w-full flex-1"
        label="Calle principal"
        :error="form.errors.main_street"
        required
        v-model="form.main_street"
      >
        <template #floating-icon>
          <Icon>location_home</Icon>
        </template>
      </TextField>
      <TextField
        class="w-full flex-1"
        label="Calles adyacentes"
        :error="form.errors.cross_streets"
        v-model="form.cross_streets"
      >
        <template #floating-icon>
          <Icon>alt_route</Icon>
        </template>
      </TextField>
    </div>
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <div class="flex flex-col gap-6 flex-1 w-full md:flex-row">
        <TextField
          class="w-full flex-[3]"
          label="Colonia"
          :error="form.errors.neighborhood"
          required
          v-model="form.neighborhood"
        >
          <template #floating-icon>
            <Icon>warehouse</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-[2]"
          label="Código postal"
          :error="form.errors.postal_code"
          required
          v-model="form.postal_code"
        >
          <template #floating-icon>
            <Icon>local_post_office</Icon>
          </template>
        </TextField>
      </div>
      <div class="flex flex-1 w-full gap-6">
        <TextField
          class="w-full flex-1"
          label="Número Exterior"
          :error="form.errors.street_number"
          v-model="form.street_number"
        >
          <template #floating-icon>
            <Icon>house</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-1"
          label="Número interior"
          :error="form.errors.suite_number"
          v-model="form.suite_number"
        >
          <template #floating-icon>
            <Icon>apartment</Icon>
          </template>
        </TextField>
      </div>
    </div>
  </Form>
</template>
