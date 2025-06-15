/// <reference types="node" />
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { getSpaConfigs } from './src/Guacamole/tools/vite-spa-configs';

const spaConfigs = getSpaConfigs();

export default defineConfig(() => {
  const input: Record<string, string> = {};
  spaConfigs.forEach((spa) => {
    const key = spa.name;
    input[key] = spa.indexHtml;
  });

  return {
    plugins: [
      vue(),
      viteStaticCopy({
        targets: [
          { src: ['src/*', '!src/public/**', '!.DS_Store'], dest: '../' },
          { src: ['src/public/*', '!.DS_Store'], dest: '.' },
        ],
        watch: {
          reloadPageOnChange: true,
        },
      }),
    ],
    build: {
      outDir: 'dist/public',
      emptyOutDir: false,
      rollupOptions: {
        input,
      },
    },
    resolve: {
      alias: spaConfigs.reduce(
        (aliases, spa) => {
          aliases[`@${spa.name}`] = spa.root;
          return aliases;
        },
        {} as Record<string, string>
      ),
    },
    server: {
      port: 5173,
      strictPort: true,
    },
  };
});
