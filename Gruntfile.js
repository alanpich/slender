'use strict';

module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-templator');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-concurrent');

    grunt.initConfig({
        templator: {
            options: {
                content: __dirname + '/_content',
                templates: __dirname + '/_templates',
                dir: __dirname
            },
            docs: {
                sausage: 'roll'
            }
        }
    });


    grunt.config('watch',{
        content: {
            files: ['_content/**/*.md'],
            tasks: ['templator:docs'],
            options: {
                spawn: true
            }
        },
        templates: {
            files: ['**/*.twig'],
            tasks: ['templator:docs'],
            options: {
                spawn: true
            }
        },
        styles: {
            files: ['_sass/**/*.scss'],
            tasks: ['sass:default','templator:docs'],
            options: {
                spawn: true
            }
        }
    });


    grunt.config('sass',{
        default: {
            options: {
                unixNewlines: true,
                style: 'compressed'
            },
            files: {                         // Dictionary of files
                'assets/styles/main.css': '_sass/main.scss'    // 'destination': 'source'
            }
        }
    });


    grunt.config('concurrent',{
        dev: ['watch:content','watch:styles','watch:templates']
    });


    grunt.registerTask('default',['sass:default','templator:docs','concurrent:dev']);
};
