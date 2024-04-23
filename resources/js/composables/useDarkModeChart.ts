import { useDark } from '@vueuse/core';
import { Chart } from 'chart.js';
import { Ref, computed, watch } from 'vue';
import colorPalette from '../../../tailwindcolorpalette.js'

export function useDarkModeChart(chart: Ref<Chart>) {
  const isDark = useDark()

  const textColor = computed(() =>
    isDark.value
      ? colorPalette['neutral-variant'][80]
      : colorPalette['neutral-variant'][30]
  )
  const backgroundColors = computed(() =>
    isDark.value
      ? [colorPalette.primary[80], colorPalette.secondary[30], colorPalette.tertiary[80]]
      : [colorPalette.primary[40], colorPalette.secondary[40], colorPalette.tertiary[40]]
  )

  watch(isDark, () => {
    chart.value.options.scales!.y!.grid!.color = textColor.value
    chart.value.options.scales!.y!.ticks!.color = textColor.value
    chart.value.options.scales!.x!.grid!.color = textColor.value
    chart.value.options.scales!.x!.ticks!.color = textColor.value
    chart.value.data.datasets[0].backgroundColor = backgroundColors.value
  
    chart.value.update()
  })

  return { textColor, backgroundColors }
}