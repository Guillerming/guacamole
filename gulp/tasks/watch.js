import { watch, series } from 'gulp';
import { copySrc } from './copy.js';
import { viteBuild } from './vite.js';
import { buildPages } from './build-pages.js';

export function watcher() {
  watch('../src/**/*.*', function (cb) {
    series(copySrc, viteBuild, buildPages)(cb);
  });
}
