'use strict';

module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-templator');

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
    })

};
