import { readdirSync, existsSync } from 'fs';
import { join, resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

export interface SpaConfig {
  name: string;
  root: string;
  indexHtml: string;
  outDir: string;
  localConfigPath?: string;
}

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const projectName = 'Site';
const rootDir = resolve(__dirname, '../../');
const UIDir = join(rootDir, projectName, 'UI');

function findSpaDirs(baseDir: string): string[] {
  const result: string[] = [];
  const entries = readdirSync(baseDir, { withFileTypes: true });
  for (const entry of entries) {
    const fullPath = join(baseDir, entry.name);
    if (entry.isDirectory()) {
      // Si contiene un index.html, es una SPA
      if (existsSync(join(fullPath, 'index.html'))) {
        result.push(fullPath);
      } else {
        // Buscar recursivamente
        result.push(...findSpaDirs(fullPath));
      }
    }
  }
  return result;
}

export function getSpaConfigs(): SpaConfig[] {
  const baseDir = resolve(UIDir);
  const spaRoots = findSpaDirs(baseDir);
  return spaRoots.map((root) => {
    const name = root.replace(baseDir + '/', '').replace(/\//g, '_');
    const indexHtml = join(root, 'index.html');
    const outDir = resolve(rootDir, `public/${name.toLowerCase()}`);
    const localConfigPath = join(root, 'vite.local.config.ts');
    return {
      name,
      root,
      indexHtml,
      outDir,
      localConfigPath: existsSync(localConfigPath) ? localConfigPath : undefined,
    };
  });
}
