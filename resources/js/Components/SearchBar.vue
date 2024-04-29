<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3'
import '@material/web/textfield/outlined-text-field'
import { onMounted, ref, watch, watchEffect } from 'vue'
import Icon from './Icon.vue'

const props = withDefaults(
  defineProps<{
    baseUrl?: string
    name?: string
  }>(),
  {
    baseUrl: location.href,
    name: 'search',
  }
)

const page = usePage()

const search = ref('')

watch(search, (value) => {
  const searchKey =
    props.name === 'search' ? props.name : `search-${props.name}`

  router.get(
    props.baseUrl,
    // @ts-expect-error immediately
    {
      ...route().params,
      page: 1,
      [searchKey]: value,
    },
    {
      preserveState: true,
      replace: true,
    }
  )
})

const onChange = () => {
  if (!page.props.filters.search) {
    search.value = ''
    return
  }

  search.value =
    typeof page.props.filters.search === 'string'
      ? page.props.filters.search
      : page.props.filters.search![props.name]
}

watchEffect(() => {
  onChange()
})

onMounted(() => {
  onChange()
})
</script>

<template>
  <md-outlined-text-field
    type="search"
    v-model="search"
  >
    <Icon slot="leading-icon">search</Icon>
  </md-outlined-text-field>
</template>

<style scoped>
md-outlined-text-field {
  --md-outlined-text-field-container-shape: 1.75rem;
}
</style>
