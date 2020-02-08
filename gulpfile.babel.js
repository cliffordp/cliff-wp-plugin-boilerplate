import {src, dest, watch, series, parallel} from 'gulp';
// Imports yargs to can retrieve commandline arguments. We use it for differentiating between production and development.
import yargs from 'yargs';
// We use gulp-sass to compile things
import sass from 'gulp-sass';
// We use gulp-clean-css for minifying
import cleanCss from 'gulp-clean-css';
// We use gulpif to differentiate if we are in production or in development
import gulpif from 'gulp-if';
//  We use postcss for transform CSS files
import postcss from 'gulp-postcss';
// We use sourcemaps for development to see from which files our CSS comes
import sourcemaps from 'gulp-sourcemaps';
// We use the autoprefixer for automatically prefix firefox, ie, chrome CSS.
import autoprefixer from 'autoprefixer';
// We use webpack-stream to process our js files
import webpack from 'webpack-stream';

// Constant used for production
const PRODUCTION = yargs.argv.prod;

export const adminstyles = () => {
	return src( 'development/admin/scss/style.scss' ) // Main style input file. Here you should make style changes
		.pipe( gulpif( !PRODUCTION, sourcemaps.init() ) ) // Initializes the sourcemaps if not in production
		.pipe( sass().on( 'error', sass.logError ) ) // Converts SCSS to CSS.
		.pipe( gulpif( PRODUCTION, postcss( [ autoprefixer ] ) ) ) // Prefixes for firefox, ie etc.
		.pipe( gulpif( PRODUCTION, cleanCss( { compatibility: '*' } ) ) ) // Minifies the CSS if in production mode; compatibility '*' ie10+ compatibility mode
		.pipe( gulpif( !PRODUCTION, sourcemaps.write() ) ) // Writes the sourcemaps if not in production
		.pipe( dest( 'src/Admin/css' ) ); // Destination folder
};

export const frontendstyles = () => {
	return src( 'development/frontend/scss/style.scss' ) // Main style input file. Here you should make frontend style changes
		.pipe( gulpif( !PRODUCTION, sourcemaps.init() ) ) // Initializes the sourcemaps if not in production
		.pipe( sass().on( 'error', sass.logError ) ) // Converts SCSS to CSS.
		.pipe( gulpif( PRODUCTION, postcss( [ autoprefixer ] ) ) ) // Prefixes for firefox, ie etc.
		.pipe( gulpif( PRODUCTION, cleanCss( { compatibility: '*' } ) ) ) // Minifies the CSS if in production mode; compatibility '*' ie10+ compatibility mode
		.pipe( gulpif( !PRODUCTION, sourcemaps.write() ) ) // Writes the sourcemaps if not in production
		.pipe( dest( 'src/Frontend/css' ) ); // Destination folder
};

/**
 * For our JavaScript part:
 *
 * First, we specify our entry file in the src() function
 * Then we pipe webpack with some options.
 * We define in the rules option to use the babel-loader to transform our JavaScript files
 * In the mode option we define if we are in production or development mode
 * In the development mode we create inline-source-maps (because they are accurate)
 * In production we don't need the sourcemap and we also minify the resulting js file.
 * The output option is there for the filename because webpack otherwise gives us a random filename
 * Then we pipe our created file to our admin/js folder.
 */
export const adminscripts = () => {
	return src( 'development/admin/js/script.js' )
		.pipe( webpack( {
			module   : {
				rules: [
					{
						test: /\.js$/,
						use : {
							loader : 'babel-loader',
							options: {
								presets: [],
							},
						},
					},
				],
			},
			mode     : PRODUCTION ? 'production' : 'development',
			devtool  : !PRODUCTION ? 'inline-source-map' : false,
			output   : {
				filename: 'script.js',
			},
			externals: {
				jquery: 'jQuery',
			},
		} ) )
		.pipe( dest( 'src/Admin/js' ) );
};

export const frontendscripts = () => {
	return src( 'development/frontend/js/script.js' )
		.pipe( webpack( {
			module   : {
				rules: [
					{
						test: /\.js$/,
						use : {
							loader : 'babel-loader',
							options: {
								presets: [],
							},
						},
					},
				],
			},
			mode     : PRODUCTION ? 'production' : 'development',
			devtool  : !PRODUCTION ? 'inline-source-map' : false,
			output   : {
				filename: 'script.js',
			},
			externals: {
				jquery: 'jQuery',
			},
		} ) )
		.pipe( dest( 'src/Frontend/js' ) );
};

// Watch task to run in development. So on save in a file everything gots recompiled.
export const watchForChanges = () => {
	watch( 'development/admin/scss/**/*.scss', adminstyles );
	watch( 'development/frontend/scss/**/*.scss', frontendstyles );
	watch( 'development/admin/js/**/*.js', adminscripts );
	watch( 'development/frontend/js/**/*.js', frontendscripts );
};

export const build = series( parallel( adminstyles, frontendstyles, adminscripts ), frontendscripts );
export const dev = series( parallel( adminstyles, frontendstyles, adminscripts ), frontendscripts, watchForChanges );
export default dev;