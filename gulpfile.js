
////////////////////////////////
// Setup
////////////////////////////////

// Gulp and package
const { src, dest, parallel, series, watch } = require('gulp');

// Plugins
const autoprefixer = require('autoprefixer');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const tildeImporter = require('node-sass-tilde-importer');
const cssnano = require('cssnano');
const pixrem = require('pixrem');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const reload = browserSync.reload;
const rename = require('gulp-rename');
const sass = require('gulp-sass')(require('sass'));
const spawn = require('child_process').spawn;
const uglify = require('gulp-uglify-es').default;
const newer = require("gulp-newer");

// Relative paths function
function pathsConfig(appName) {

    const vendorsRoot = 'node_modules';

    return {
        vendorsJs: [
            `${vendorsRoot}/@popperjs/core/dist/umd/popper.js`,
            `${vendorsRoot}/bootstrap/dist/js/bootstrap.js`,
            `${vendorsRoot}/smooth-scroll/dist/smooth-scroll.min.js`,
            `${vendorsRoot}/vanilla-tilt/dist/vanilla-tilt.min.js`,
            `${vendorsRoot}/aos/dist/aos.js`,
            `${vendorsRoot}/nouislider/dist/nouislider.min.js`,
            `${vendorsRoot}/parallax-js/dist/parallax.min.js`,
            `${vendorsRoot}/jarallax/dist/jarallax.min.js`,
            `${vendorsRoot}/img-comparison-slider/dist/index.js`,
            `${vendorsRoot}/shufflejs/dist/shuffle.min.js`,
            `${vendorsRoot}/simplebar/dist/simplebar.js`,
            `${vendorsRoot}/swiper/swiper-bundle.min.js`,
            `${vendorsRoot}/@lottiefiles/lottie-player/dist/lottie-player.js`,
            `${vendorsRoot}/lightgallery/lightgallery.min.js`,
            `${vendorsRoot}/lightgallery/plugins/zoom/lg-zoom.min.js`,
            `${vendorsRoot}/lightgallery/plugins/fullscreen/lg-fullscreen.min.js`,
            `${vendorsRoot}/lightgallery/plugins/video/lg-video.min.js`,
            `${vendorsRoot}/lightgallery/plugins/thumbnail/lg-thumbnail.min.js`,
            `${vendorsRoot}/imagesloaded/imagesloaded.pkgd.js`,
            `${vendorsRoot}/cleave.js/dist/cleave.min.js`,
            `${vendorsRoot}/prismjs/components/prism-core.min.js`,
            `${vendorsRoot}/prismjs/components/prism-markup.min.js`,
            `${vendorsRoot}/prismjs/components/prism-clike.min.js`,
            `${vendorsRoot}/prismjs/plugins/toolbar/prism-toolbar.min.js`,
            `${vendorsRoot}/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js`,
            `${vendorsRoot}/prismjs/plugins/line-numbers/prism-line-numbers.min.js`,
            `${vendorsRoot}/rellax/rellax.min.js`
        ],
        vendorsCSS: [
            `${vendorsRoot}/swiper/swiper-bundle.min.css`,
            `${vendorsRoot}/boxicons/css/boxicons.min.css`,
            `${vendorsRoot}/img-comparison-slider/dist/styles.css`,
            `${vendorsRoot}/aos/dist/aos.css`,
            `${vendorsRoot}/nouislider/dist/nouislider.min.css`,
            `${vendorsRoot}/lightgallery/css/lightgallery.css`,
            `${vendorsRoot}/lightgallery/css/lightgallery-bundle.css`,
            `${vendorsRoot}/lightgallery/css/lg-zoom.css`,
            `${vendorsRoot}/lightgallery/css/lg-thumbnail.css`,
            `${vendorsRoot}/jarallax/dist/jarallax.min.css`,
            `${vendorsRoot}/simplebar/dist/simplebar.min.css`,
            `${vendorsRoot}/prismjs/themes/prism.min.css`,
            `${vendorsRoot}/prismjs/plugins/toolbar/prism-toolbar.min.css`,
            `${vendorsRoot}/prismjs/plugins/line-numbers/prism-line-numbers.min.css`,
        ],

        templates: `./`,

        css: `./assets/css`,
        scss: `./assets/scss`,
        js: `./assets/js`,
    };
}

const paths = pathsConfig();

////////////////////////////////
// Tasks
////////////////////////////////
const processCss = [
    autoprefixer(), // adds vendor prefixes
    pixrem(), // add fallbacks for rem units
];

const minifyCss = [
    cssnano({ preset: 'default' }), // minify result
];

// Styles autoprefixing and minification
function styles() {

    return src(`${paths.scss}/theme.scss`)
        .pipe(
            sass({
                importer: tildeImporter,
                includePaths: [paths.scss],
            }).on('error', sass.logError),
        )
        .pipe(plumber()) // Checks for errors
        .pipe(postcss(processCss))
        .pipe(dest(paths.css))
        .pipe(rename({ suffix: '.min' }))
        .pipe(postcss(minifyCss)) // Minifies the result
        .pipe(dest(paths.css));
}


// Vendor Javascript minification
function vendorScripts() {
    return src(paths.vendorsJs, { sourcemaps: true })
        .pipe(concat('vendors.js'))
        .pipe(dest(paths.js))
        .pipe(plumber()) // Checks for errors
        .pipe(uglify()) // Minifies the js
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(paths.js, { sourcemaps: '.' }));
}

// Vendor CSS minification
function vendorStyles() {
    return src(paths.vendorsCSS, { sourcemaps: true })
        .pipe(concat('vendors.css'))
        .pipe(plumber()) // Checks for errors
        .pipe(postcss(processCss))
        .pipe(dest(paths.css))
        .pipe(rename({ suffix: '.min' }))
        .pipe(postcss(minifyCss)) // Minifies the result
        .pipe(dest(paths.css));
}


// File Watch Task
function watchFiles() {
    watch(paths.scss + "scss/**/*.scss", series(styles));
    watch(`${paths.templates}/**/**/*.php`).on('change', reload);
}

// Production Tasks
exports.default = series(
    parallel(styles, vendorScripts, vendorStyles),
    parallel(watchFiles)
);

// Build Tasks
exports.build = series(
    parallel(styles, vendorScripts, vendorStyles)
);