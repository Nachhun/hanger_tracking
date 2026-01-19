// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,
  compatibilityDate: '2025-07-15',
  devtools: { enabled: false },
  app: {
    head: {
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' },
        { name: 'format-detection', content: 'telephone=no' }
      ],
      script: [
        { src: 'https://telegram.org/js/telegram-web-app.js' }
      ]
    }
  },
  css: ['~/assets/css/main.css'],
  postcss: {
    plugins: {
      '@tailwindcss/postcss': {},
      autoprefixer: {},
    },
  },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || '/api',
    },
  },
  nitro: {
    devProxy: {
      '/storage': {
        target: 'http://localhost:8000/storage',
        changeOrigin: true,
      }
    },
    routeRules: {
      '/api/**': { proxy: 'http://localhost:8000/api/**' },
    },
  },
  vite: {
    server: {
      allowedHosts: true,
    },
  },
})
