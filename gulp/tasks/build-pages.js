import { src, dest } from 'gulp';
import path from 'path';
import { deleteAsync } from 'del';
import through from 'through2';
import crypto from 'crypto';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
import ts from 'gulp-typescript';
import sourcemaps from 'gulp-sourcemaps';
import fs from 'fs';
import { fileURLToPath } from 'url';

const PAGES_SRC = '../../src/Site/UI/Pages';
const PAGES_DIST = '../dist/public/assets/pages/';

function isSpaProject(pageDir) {
  // Check for Vue SPA: presence of .vue or vite config
  const files = fs.readdirSync(pageDir);
  if (files.some((f) => f.endsWith('.vue')) || files.some((f) => f.startsWith('vite'))) return true;
  // Check for React SPA: presence of .jsx/.tsx or vite config
  if (files.some((f) => f.endsWith('.jsx') || f.endsWith('.tsx'))) return true;
  // Check for SPA manifest file
  if (files.includes('isspa') || files.includes('isspa.json')) return true;
  return false;
}

function hashFile() {
  return through.obj(function (file, _, cb) {
    if (file.isBuffer()) {
      const hash = crypto.createHash('md5').update(file.contents).digest('hex').slice(0, 8);
      const ext = path.extname(file.path);
      file.path = path.join(path.dirname(file.path), `${hash}${ext}`);
    }
    cb(null, file);
  });
}

async function cleanPages() {
  await deleteAsync([`${PAGES_DIST}**/*`], { force: true });
}

function buildTS(pageDir, page) {
  return src(`${pageDir}/*.ts`)
    .pipe(sourcemaps.init())
    .pipe(ts.createProject('../../../../tsconfig.json')())
    .pipe(hashFile())
    .pipe(sourcemaps.write('.'))
    .pipe(dest(`${PAGES_DIST}${page}/`));
}

function buildJS(pageDir, page) {
  return src(`${pageDir}/*.js`)
    .pipe(hashFile())
    .pipe(dest(`${PAGES_DIST}${page}/`));
}

function buildSCSS(pageDir, page) {
  const sassCompiler = gulpSass(sass);
  return src(`${pageDir}/*.scss`)
    .pipe(sourcemaps.init())
    .pipe(sassCompiler().on('error', sassCompiler.logError))
    .pipe(hashFile())
    .pipe(sourcemaps.write('.'))
    .pipe(dest(`${PAGES_DIST}${page}/`));
}

function buildCSS(pageDir, page) {
  return src(`${pageDir}/*.css`)
    .pipe(hashFile())
    .pipe(dest(`${PAGES_DIST}${page}/`));
}

export async function buildPages(cb) {
  await cleanPages();
  const __filename = fileURLToPath(import.meta.url);
  const __dirname = path.dirname(__filename);
  const pagesRoot = path.resolve(__dirname, PAGES_SRC);
  const pageDirs = fs
    .readdirSync(pagesRoot, { withFileTypes: true })
    .filter((dirent) => dirent.isDirectory())
    .map((dirent) => dirent.name);

  for (const page of pageDirs) {
    const pageDir = path.join(pagesRoot, page);
    if (isSpaProject(pageDir)) {
      // Skip SPA projects
      continue;
    }
    buildTS(pageDir, page);
    buildJS(pageDir, page);
    buildSCSS(pageDir, page);
    buildCSS(pageDir, page);
  }
  cb();
}
