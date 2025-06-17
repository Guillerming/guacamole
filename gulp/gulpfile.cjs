const { series, parallel } = require('gulp');
const watcher = require('./tasks/watch.cjs');
const copy = require('./tasks/copy.cjs');
const vite = require('./tasks/vite.cjs');

exports.default = series(copy, vite);

exports.watch = watcher;
