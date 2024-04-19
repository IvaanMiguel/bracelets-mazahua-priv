<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  text: string
  search: string
}>()

const normalize = (str: string) => {
  return str
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
}

const html = computed(() => {
  const text = normalize(props.text)
  const search = normalize(props.search)
  const first = text.indexOf(search)

  if (first === -1 || !props.search) return props.text

  const last = first + search.length

  const start = props.text.substring(0, first)
  const middle = props.text.substring(first, last)
  const end = props.text.substring(last, text.length)

  return `
    ${start}<span class="font-black">${middle}</span>${end}
  `
})
</script>

<template>
  <span v-html="html"></span>
</template>
