const { exec } = require('child_process');
const path = require('path');

function viteBuild(cb) {
  exec('vite build', { cwd: path.resolve(__dirname, '../../') }, (err, stdout, stderr) => {
    if (stdout) process.stdout.write(stdout);
    if (stderr) process.stderr.write(stderr);
    cb(err);
  });
}

module.exports = viteBuild;