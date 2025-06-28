import { exec } from 'child_process';
import path from 'path';
import { fileURLToPath } from 'url';

export function viteBuild(cb) {
  const __filename = fileURLToPath(import.meta.url);
  const __dirname = path.dirname(__filename);
  exec('vite build', { cwd: path.resolve(__dirname, '../../') }, (err, stdout, stderr) => {
    if (stdout) process.stdout.write(stdout);
    if (stderr) process.stderr.write(stderr);
    cb(err);
  });
}
