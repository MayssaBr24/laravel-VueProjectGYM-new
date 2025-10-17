import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
  server: {
    port: 5173,
    cors: {
      origin: ['http://127.0.0.1:8000', 'http://localhost:9000'],
      credentials: true,
    },
  },

  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),

    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),

    VitePWA({
      registerType: 'autoUpdate', // met à jour automatiquement le service worker
      includeAssets: [
        'favicon.ico',
        'apple-touch-icon.jpeg',
        'masked-icon.png',
      ],
      manifest: {
        name: 'Mon Application PWA',
        short_name: 'MonApp',
        description: 'Une application Laravel + Vue.js en mode PWA',
        theme_color: '#ffffff',
        background_color: '#ffffff',
        display: 'standalone',
        start_url: '/',
        icons: [
          {
            src: 'pwa-192x192.png',
            sizes: '192x192',
            type: 'image/png',
          },
          {
            src: 'pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png',
          },
          {
            src: 'pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png',
            purpose: 'any maskable',
          },
        ],
      },
    }),
  ],

  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
})
