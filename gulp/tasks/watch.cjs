const { watch, series } = require('gulp');
const copy = require('./copy.cjs');
const vite = require('./vite.cjs');

function watcher() {
  watch('../src/**/*.*', function(cb) {
    series(copy, vite)(cb);
  });
}

module.exports = watcher;
