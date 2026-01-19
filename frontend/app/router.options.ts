import type { RouterConfig } from '@nuxt/schema'

export default <RouterConfig>{
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        }

        // If hash exists and is a valid selector (not Telegram data)
        if (to.hash && !to.hash.startsWith('#tgWebAppData')) {
            return {
                el: to.hash,
                top: 0,
                behavior: 'smooth'
            }
        }

        return { top: 0, left: 0 }
    }
}
