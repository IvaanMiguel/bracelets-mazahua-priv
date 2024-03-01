import { MdDialog } from '@material/web/dialog/dialog'
import { onMounted, ref } from 'vue'

export function useModal(querySelector: string) {
  const modal = ref<MdDialog | null>(null)

  onMounted(() => {
    modal.value = document.body.querySelector(querySelector)
  })

  return { modal: modal }
}
