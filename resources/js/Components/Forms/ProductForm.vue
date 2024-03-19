<script setup lang="ts">
import { productRules } from '@/rules/product'
import { IdCategory } from '@/types/categories'
import { Product } from '@/types/products'
import { useForm, usePage } from '@inertiajs/vue3'
import '@material/web/iconbutton/filled-icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import { onMounted, onUnmounted, ref, watch } from 'vue'
import Form from '../Form.vue'
import Icon from '../Icon.vue'
import Modal from '../Modal.vue'
import TextField from '../TextField.vue'

const props = defineProps<{ defaults?: Product }>()

const page = usePage()
const form = useForm<Product>({
  name: props.defaults?.name || '',
  category_id: props.defaults?.category_id,
  price: props.defaults?.price,
  stock: props.defaults?.stock,
})

const categoriesModal = ref<InstanceType<typeof Modal>>()
const categoryTextField = ref<InstanceType<typeof TextField>>()
const filter = ref(page.props.categories as IdCategory[])
const categoryName = ref<string>()
const searchCategory = ref('')

const categoryOnClick = (e: KeyboardEvent) => {
  if (e.key === ' ') {
    e.preventDefault()
    categoriesModal.value?.modal?.show()
  }
}

const categoryDoubleClick = () => {
  categoriesModal.value?.modal?.show()
}

const setCategory = (category: IdCategory) => {
  form.category_id = category.id

  categoriesModal.value?.modal?.close()
  searchCategory.value = ''
}

watch(
  () => form.category_id,
  (value) => {
    const categories = page.props.categories as IdCategory[]
    const selectedCategory = categories.find((category) => {
      return category.id === value
    })

    categoryName.value = selectedCategory?.name
  }
)

watch(searchCategory, (value) => {
  const categories = page.props.categories as IdCategory[]
  filter.value = categories.filter((category) => {
    return category.name.toUpperCase().startsWith(value.toUpperCase())
  })
})

onMounted(() => {
  categoryTextField.value?.input?.addEventListener('keydown', categoryOnClick)
  categoryTextField.value?.input?.addEventListener(
    'dblclick',
    categoryDoubleClick
  )
})

onUnmounted(() => {
  categoryTextField.value?.input?.removeEventListener(
    'keydown',
    categoryOnClick
  )
  categoryTextField.value?.input?.removeEventListener(
    'dblclick',
    categoryDoubleClick
  )
})

defineExpose({ form })
</script>

<template>
  <Form
    :inertia-form="form"
    :rules="productRules"
    v-bind="$attrs"
  >
    <TextField
      class="col-span-2"
      label="Nombre"
      required
      minlength="3"
      maxlength="255"
      :error="form.errors.name"
      v-model="form.name"
    >
      <template #floating-icon>
        <Icon>shopping_bag</Icon>
      </template>
    </TextField>
    <TextField
      ref="categoryTextField"
      class="col-span-2"
      required
      readonly
      placeholder="Categoría*"
      supporting-text="Presiona Espacio o doble clic para editar."
      :label="form.category_id ? 'Categoría' : null"
      :error="form.errors.category_id"
      @click="categoryOnClick"
      v-model="categoryName"
    >
      <template #floating-icon>
        <Icon>category</Icon>
      </template>
    </TextField>
    <TextField
      label="Precio"
      required
      minlength="1"
      maxlength="7"
      prefix-text="$"
      suffix-text="MXN"
      :error="form.errors.price"
      v-model="form.price"
    >
      <template #floating-icon>
        <Icon>attach_money</Icon>
      </template>
    </TextField>
    <TextField
      label="Stock"
      minlength="1"
      maxlength="3"
      :error="form.errors.stock"
      v-model="form.stock"
    >
      <template #floating-icon>
        <Icon>inventory</Icon>
      </template>
    </TextField>
  </Form>

  <Modal
    ref="categoriesModal"
    class="h-2/3 w-full"
  >
    <div slot="headline">Selecciona una categoría</div>
    <div
      slot="content"
      class="px-0"
    >
      <md-outlined-text-field
        class="w-full px-4"
        placeholder="Buscar..."
        v-model="searchCategory"
      >
        <Icon slot="leading-icon">search</Icon>
      </md-outlined-text-field>
      <md-list class="bg-transparent">
        <template v-for="(category, i) in filter">
          <md-list-item
            type="button"
            @click="setCategory(category)"
          >
            {{ category.name }}
          </md-list-item>
          <md-divider
            v-if="i !== filter.length - 1"
            inset
          />
        </template>
      </md-list>
    </div>

    <div slot="actions">
      <md-text-button @click="categoriesModal?.modal?.close">
        Cancelar
      </md-text-button>
    </div>
  </Modal>
</template>

<style scoped>
md-outlined-text-field {
  --md-outlined-text-field-container-shape: 1.75rem;
}
</style>
