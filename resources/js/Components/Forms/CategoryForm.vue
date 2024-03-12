<script setup lang="ts">
import { categoryRules } from '@/rules/category'
import { Category } from '@/types/categories'
import { useForm } from '@inertiajs/vue3'
import Form from '../Form.vue'
import Icon from '../Icon.vue'
import TextField from '../TextField.vue'

const props = defineProps<{ defaults?: Category }>()

const form = useForm<Category>({
  name: props.defaults?.name || '',
})

defineExpose({ form })
</script>

<template>
  <Form
    :inertia-form="form"
    :rules="categoryRules"
  >
    <TextField
      label="Nombre"
      required
      minlength="3"
      maxlength="255"
      :error="form.errors.name"
      v-model="form.name"
    >
      <template #floating-icon>
        <Icon>package_2</Icon>
      </template>
    </TextField>
  </Form>
</template>
