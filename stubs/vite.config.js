import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig((command) => ({
  base:
    command === 'serve'
      ? process.env.ASSET_URL || ''
      : `${process.env.ASSET_URL || ''}/dist/`,
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
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'),
    },
  },
}))
