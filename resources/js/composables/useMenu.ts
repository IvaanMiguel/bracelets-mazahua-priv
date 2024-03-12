import { MdMenu } from '@material/web/menu/menu'
import { useEventListener } from '@vueuse/core'

export function useMenu(anchorSelector: string, menuSelector: string) {
  const anchor = document.body.querySelector(anchorSelector) as HTMLElement
  const menu = document.body.querySelector(menuSelector) as MdMenu

  const openMenu = () => {
    menu.open = !menu.open
  }

  useEventListener(anchor, 'click', openMenu)
}
