import ElementPlus from 'unplugin-element-plus/vite'
import Vue from '@vitejs/plugin-vue'
import path from 'path'
import { defineConfig, loadEnv } from 'vite'

export default defineConfig(({ command, mode}) => {
  const { VITE_HOST, VITE_PORT } = loadEnv(mode, process.cwd(), '')

  return {
    server: {
      host: VITE_HOST ?? 'http://localhost',
      port: VITE_PORT ?? 3000
    },

    base: command === 'serve' ? '/' : '/dist/',

    build: {
      publicDir: false,
      manifest: true,
      outDir: 'public/dist',
      rollupOptions: {
        input: 'resources/js/app.js',
      },
    },

    optimizeDeps: {
      exclude: ['public/storage'],
    },

    plugins: [Vue(), ElementPlus()],

    resolve: {
      alias: {
        '@': path.resolve(__dirname, './resources/js'),
      },
    },
  }
})
