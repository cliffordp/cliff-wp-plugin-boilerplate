import { src, dest, watch } from 'gulp';
//Imports yargs to can retrieve commandline arguments. We use it for differentiating between production and development.
import yargs from 'yargs';
//We use gulp-sass to compile things
import sass from 'gulp-sass';
//We use gulp-clean-css for minifying
import cleanCss from 'gulp-clean-css';
//We use gulpif to differentiate if we are in production or in development
import gulpif from 'gulp-if';
// We use postcss for transfrom css files
import postcss from 'gulp-postcss';
//We use sourcemaps for development to see from which files our css comes
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';

//Constant used for production
const PRODUCTION = yargs.argv.prod;

export const adminstyles = () => {
    return src('development/admin/scss/style.scss') //Main style input file. Here you should make style changes
        .pipe(gulpif(!PRODUCTION, sourcemaps.init())) //Initializes the sourcemaps if not in production
        .pipe(sass().on('error', sass.logError)) //Converts scss to css.
        .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ]))) //prefixes for firefox, ie etc.
        .pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'}))) //Minifys the css if in production mode
        .pipe(gulpif(!PRODUCTION, sourcemaps.write())) //writes the sourcemaps if not in production
        .pipe(dest('src/admin/css')); //Destination folder
}

export const frontendstyles = () => {
    return src('development/frontend/scss/style.scss') //Main style input file. Here you should make frontend style changes
        .pipe(gulpif(!PRODUCTION, sourcemaps.init())) //Initializes the sourcemaps if not in production
        .pipe(sass().on('error', sass.logError)) //Converts scss to css.
        .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ]))) //prefixes for firefox, ie etc.
        .pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'}))) //Minifys the css if in production mode
        .pipe(gulpif(!PRODUCTION, sourcemaps.write())) //writes the sourcemaps if not in production
        .pipe(dest('src/frontend/css')); //Destination folder
}

// Watch task to run in development. So on save in a file everything gots recompiled.
export const watchForChanges = () => {
    watch('development/admin/scss/**/*.scss', adminstyles);
    watch('development/frontend/scss/**/*.scss', frontendstyles);
}