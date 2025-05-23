module.exports = function(grunt) {
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.initConfig({
    watch: {
      scripts: {
        files: [
          './_templates/**/*.*',
          './*.php',
          './css/**/*.css',
          './js/**/*.js'
        ],
        options: {
          livereload: true,
          spawn: false
        }
      }
    }
  });

  grunt.registerTask('default', ['watch']);
};
