module.exports = function (grunt) {
    "use strict";
    // Initial Config
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        themeName: 'tonic3-test',

        watch: {
            styles: {
                files: [
                    'views/css/source/**/*.scss',
                    'views/css/source/**/**/*.scss'
                ],
                tasks: ['sass'],
                options: {
                    interrupt: true,
                    livereload: true
                }
            }
        },

        sass: {
            dev: {
                files: [{
                    expand: true,
                    cwd: 'views/css/source/',
                    src: '*.scss',
                    dest: 'views/css/compiled/',
                    ext: '.css'
                }],
                options: {
                    style: 'expanded',
                    lineNumbers: true,
                    cacheLocation: 'tempcache'
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.registerTask('default', ['watch']);

};
