import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import glob from 'glob';
import path from 'path';

const pages = glob.sync('src/**.js').reduce(function (obj, el) {
  obj[path.parse(el).name] = el;
  return obj
}, {});

export default defineConfig({
  plugins: [vue()],
  build: {
    chunkSizeWarningLimit: 1500,
    sourcemap: true,
    rollupOptions: {
      input: pages,
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name].[ext]'
      }
    }
  }
})
