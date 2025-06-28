import { src, dest } from 'gulp';

export function copySrc() {
  return src('../src/**/*', { dot: true }).pipe(dest('../dist/'));
}
