import { series } from 'gulp';
import { watcher } from './tasks/watch.js';
import { copySrc } from './tasks/copy.js';
import { viteBuild } from './tasks/vite.js';
import { buildPages } from './tasks/build-pages.js';

const defaultTask = series(buildPages, copySrc, viteBuild);
const watch = series(defaultTask, watcher);

export default defaultTask;
export { watcher, watch };
