module.exports = function(grunt) {

  // Load all tasks automatically
  require('load-grunt-tasks')(grunt);

  // Show elapsed task time when running
  require('time-grunt')(grunt);

  grunt.initConfig({
    // Read package.json file
    pkg: grunt.file.readJSON('package.json'),

    uglify: {
      release: {
        options: {
          mangle: true
        },
        files: {
          'js/api/upload.min.js': 'js/api/upload.js'
        }
      }
    },

    replace: {
      bump: {
        overwrite: true,
        src: [
          'wistia-api.php'
        ],
        replacements: [
          {
            from: /(Version:\s*)(?:.*)/i,
            to: '$1<%= pkg.version %>'
          },
          {
            from: /(VERSION\s*=\s*)(?:('.*'|".*"));/,
            to: '$1\'<%= pkg.version %>\';'
          }
        ]
      }
    }
  });

  grunt.registerTask('release', ['uglify:release', 'replace:bump']);

  // Register the default task
  grunt.registerTask('default', ['release']);

  // Register your own tasks here
  // Example:
  // grunt.registerTask('taskname', ['tasks', 'to', 'run']);

};
