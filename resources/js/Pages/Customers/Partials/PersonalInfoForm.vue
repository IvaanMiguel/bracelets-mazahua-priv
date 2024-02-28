<script setup lang="ts">
import { Customer } from '@/types/customers'

import { InertiaForm } from '@inertiajs/vue3'

import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import TextField from '@/Components/TextField.vue'

defineProps<{
  submit: (e?: Event) => void
  form: InertiaForm<Customer>
}>()
</script>

<template>
  <h1
    class="mb-4 text-xl font-medium text-light-on-background dark:text-dark-on-background"
  >
    Datos personales
  </h1>
  <Form
    class="flex flex-col"
    :submit="submit"
  >
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <TextField
        class="w-full flex-1"
        label="Nombre(s)"
        :error="form.errors.name"
        required
        v-model="form.name"
      >
        <template #floating-icon>
          <Icon>person</Icon>
        </template>
      </TextField>
      <TextField
        class="w-full flex-1"
        label="Apellido(s)"
        :error="form.errors.last_name"
        required
        empty-floating-icon
        v-model="form.last_name"
      />
    </div>
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <TextField
        class="w-full flex-1"
        label="Número celular"
        :error="form.errors.phone_number"
        required
        v-model="form.phone_number"
      >
        <template #floating-icon>
          <Icon>phone</Icon>
        </template>
      </TextField>
      <TextField
        class="w-full flex-1"
        label="Email"
        :error="form.errors.email"
        v-model="form.email"
      >
        <template #floating-icon>
          <Icon>email</Icon>
        </template>
      </TextField>
    </div>
    <TextField
      class="md:w-[calc(50%-(1.5rem/2))]"
      type="date"
      :error="form.errors.birth_date"
      label="Fecha de nacimiento"
      v-model="form.birth_date"
    >
      <template #floating-icon>
        <Icon>cake</Icon>
      </template>
    </TextField>
  </Form>
</template>
