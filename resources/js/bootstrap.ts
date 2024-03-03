// @ts-nocheck

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import { MdDialog } from '@material/web/dialog/dialog.js'
import axios from 'axios'

window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Material web components workaround to issue No. 5500.
 * https://github.com/material-components/material-web/issues/5500
 */

MdDialog.addInitializer(async (instance: MdDialog) => {
  await instance.updateComplete
  instance.dialog.prepend(instance.scrim)
  instance.scrim.style.inset = 0
  instance.scrim.style.zIndex = -1

  const { getOpenAnimation, getCloseAnimation } = instance
  instance.getOpenAnimation = () => {
    const animations = getOpenAnimation.call(this)
    animations.container = [...animations.container, ...animations.dialog]
    animations.dialog = []
    return animations
  }
  instance.getCloseAnimation = () => {
    const animations = getCloseAnimation.call(this)
    animations.container = [...animations.container, ...animations.dialog]
    animations.dialog = []
    return animations
  }
})

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
