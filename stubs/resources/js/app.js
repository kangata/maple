import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

import './bootstrap'
import '../css/app.css'

const appName =
  window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

const asyncViews = () => {
  return import.meta.glob('./Pages/**/*.vue')
}

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) => {
    if (import.meta.env.DEV) {
      return (await import(`./Pages/${name}.vue`)).default
    } else {
      const pages = asyncViews()
      const importPage = pages[`./Pages/${name}.vue`]

      return importPage().then((module) => module.default)
    }
  },
  setup({ el, app, props, plugin }) {
    return createApp({ render: () => h(app, props) })
      .use(plugin)
      .mixin({ methods: { route } })
      .mount(el)
  },
})

InertiaProgress.init({ color: '#4B5563' })
