require('laravel-elixir-webpack');
var elixir = require('laravel-elixir');
var gulp = require('gulp');
var less = require('gulp-less');
var minifyCSS = require('gulp-csso');

elixir(function (mix) {
    var dependencyPath = '../../../node_modules/';
    var bootstrapPath = 'node_modules/bootstrap-sass/assets';
    var mdbPath = 'node_modules/mdbootstrap';
    mix.sass('app.scss');
    mix.webpack(
            'notification.js', {}, 'resources/assets/js/bundle.js'
        )
        .scripts([
            dependencyPath + 'jquery/dist/jquery.min.js',
            dependencyPath + 'datatables.net/js/jquery.dataTables.js',
            dependencyPath + 'datatables.net-bs/js/dataTables.bootstrap.js',
            dependencyPath + 'tether/dist/js/tether.min.js',
            dependencyPath + 'bootstrap/dist/js/bootstrap.min.js',
            dependencyPath + 'leaflet/dist/leaflet.js',
            dependencyPath + 'mapbox-gl/dist/mapbox-gl.js',
            dependencyPath + 'moment/moment.js',
            dependencyPath + 'textarea-autogrow/textarea-autogrow.js',
            'plugins/mdb.min.js',
            'plugins/dataTables.mdbootstrap.js',
            'bundle.js',
            'app.js'
        ], 'public/js/app.js')
        .copy(mdbPath + '/font', 'public/font')
        .copy(bootstrapPath + '/font', 'public/fonts')
        .copy(bootstrapPath + '/javascripts/bootstrap.min.js', 'public/js');
    mix.version(['public/css/app.css', 'public/js/app.js']);
});
