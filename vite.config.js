// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel(['resources/js/app.js', 'resources/scss/app.scss']),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  
  resolve: {
    alias: {
      '@js': path.resolve(__dirname, 'resources/js'), // This sets up the '@' alias to point to the 'src' directory
      '@css': path.resolve(__dirname, 'resources/css'), // This sets up the '@' alias to point to the 'src' directory
      '@scss': path.resolve(__dirname, 'resources/scss'), // This sets up the '@' alias to point to the 'src' directory
      '@public': path.resolve(__dirname, 'public/storage'), // This sets up the '@' alias to point to the 'src' directory
      'vue': 'vue/dist/vue.esm-bundler.js',
      // You can add more aliases as needed
      // For example:
      // 'components': path.resolve(__dirname, 'src/components'),
      // 'utils': path.resolve(__dirname, 'src/utils'),
    },
  },
});