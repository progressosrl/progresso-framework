module.exports = function(grunt) {

    grunt.initConfig({
        exec: {
            scoper: 'php-scoper add-prefix --config=config/scoper.inc.php --force',
            composer: 'composer dump-autoload --working-dir build --classmap-authoritative',
        },
        copy: {
            composer: {
                src: 'composer.lock',
                dest: 'build/composer.lock',
            },
            cmb2: {
                expand: true,
                src: 'vendor/cmb2/**',
                dest: 'build/',
            }
        },
    });

    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-exec');

    grunt.registerTask('build', ['exec:scoper', 'copy:cmb2', 'copy:composer', 'exec:composer']);

};