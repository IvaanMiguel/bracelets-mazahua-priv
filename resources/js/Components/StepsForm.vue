<script setup lang="ts">
import '@material/web/button/filled-button'
import '@material/web/button/text-button'
import '@material/web/progress/circular-progress'
import '@material/web/progress/linear-progress'
import { computed, onMounted, ref, watch } from 'vue'
import Icon from './Icon.vue'

const props = withDefaults(
  defineProps<{
    steps: number
    processing?: boolean
    submit: () => void
    nextValidations?: boolean[]
    nextActions?: { [x: number]: CallableFunction }
  }>(),
  {
    processing: false,
    disableNext: false,
    nextValidations: () => [false],
  }
)

const stepsContainer = ref<HTMLDivElement>()
const activeIndex = ref(0)
const progressValue = computed(() => {
  return (activeIndex.value + 1) / props.steps
})
const stepTranslation = computed(() => {
  return `${-activeIndex.value * 100}%`
})

const next = () => {
  if (activeIndex.value + 1 === props.steps) {
    props.submit()
    return
  }

  // Probably unnecessary.
  if (activeIndex.value + 1 >= props.steps) return

  activeIndex.value++
}

const back = () => {
  if (activeIndex.value <= 0) return

  activeIndex.value--
}

const onChange = () => {
  if (!stepsContainer.value) return

  for (let i = 0; i < stepsContainer.value.children.length; i++) {
    i === activeIndex.value
      ? stepsContainer.value?.children[i].removeAttribute('inert')
      : stepsContainer.value?.children[i].setAttribute('inert', '')
  }
}

watch(activeIndex, () => {
  onChange()
})

onMounted(() => {
  onChange()
})

defineExpose({ next, back, activeIndex })
</script>

<template>
  <div>
    <md-linear-progress :value="progressValue" />
    <div class="relative flex h-full flex-col overflow-y-clip">
      <div
        class="absolute left-0 top-0 z-[1] grid h-full w-full place-items-center bg-neutral-variant-70 bg-opacity-50"
        v-show="processing"
      >
        <md-circular-progress indeterminate />
      </div>
      <div
        id="steps-container"
        ref="stepsContainer"
        class="relative grid h-full overflow-x-hidden"
      >
        <slot name="step" />
      </div>
    </div>
    <div class="p-4">
      <div class="text-end">
        <md-text-button
          class="me-4"
          :disabled="activeIndex === 0 || processing"
          @click="back"
        >
          <Icon slot="icon">arrow_back</Icon>
          Atrás
        </md-text-button>
        <md-filled-button
          trailing-icon
          :disabled="nextValidations[activeIndex] || processing"
          @click="nextActions![activeIndex] ? nextActions![activeIndex]() : next()"
        >
          Siguiente
          <Icon slot="icon">arrow_forward</Icon>
        </md-filled-button>
      </div>
    </div>
  </div>
</template>

<style scoped>
#steps-container {
  grid-template-columns: repeat(v-bind(steps), 100%);
}

:slotted(div) {
  transform: translateX(v-bind(stepTranslation));
  transition: transform 0.2s ease-in-out 0s;
  /* padding: 1rem; */
}
</style>
