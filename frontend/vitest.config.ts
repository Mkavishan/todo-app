import {defineConfig} from 'vitest/config';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [
    vue()
  ],
  test: {
    globals: true,
    environment: 'jsdom',
    coverage: {
      provider: 'v8', // or 'istanbul'
      reporter: ['text', 'html'], // coverage report formats
    },
  }
});