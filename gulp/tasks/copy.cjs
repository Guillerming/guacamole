const { src, dest } = require('gulp');

function copySrc() {
  return src('../src/**/*', { dot: true }).pipe(dest('../dist/'));
}

module.exports = copySrc;
