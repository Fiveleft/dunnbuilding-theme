module.exports = function(grunt) {

  // load all grunt tasks
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);




  grunt.initConfig({

    config : grunt.file.readJSON('./config.json'),

    // Watches for changes and runs tasks
    watch : {
      sass : {
        files : ['scss/**/*.scss'],
        tasks : ['sass:global'],
      },
      css : {
        files : ['css/**/*.css'],
        options : {
          livereload : true
        }
      },
      js : {
        files : ['js/**/*.js'],
        tasks : ['jshint'],
        options : {
          livereload : true
        }
      },
      php : {
        files : ['**/*.php'],
        options : {
          livereload : true
        }
      }
    },

    // Clean build directory
    clean: {
      build: [ 'build' ],
      dest: [ '../<%= config.info.theme_slug %>' ]
    },

    // JsHint your javascript
    jshint : {
      all : ['js/*.js', '!js/modernizr.js', '!js/require.js', '!js/*.min.js', '!js/vendor/**/*.js'],
      options : {
        browser: true,
        curly: false,
        eqeqeq: false,
        eqnull: true,
        expr: true,
        immed: true,
        newcap: true,
        noarg: true,
        smarttabs: true,
        sub: true,
        undef: false
      }
    },
    
    // Build Stylesheets from SCSS files
    sass: {
      global: {
        options: {
          sourceMap: true,
          sourceComments: false,
          outputStyle: 'compressed',
          includePaths: require('node-bourbon').includePaths
        },
        files: [{
          expand: true,
          cwd: 'scss',
          src: ['**/*.scss'],
          dest: 'css',
          ext: '.css'
        }],
      },
      production: {
        options: {
          sourceMap: true,
          sourceComments: false,
          outputStyle: 'compact',
          includePaths: require('node-bourbon').includePaths
        },
        files: [{
          expand: true,
          cwd: 'scss',
          src: ['**/*.scss'],
          dest: 'build/css',
          ext: '.css'
        }],
      }
    }, 

    // Bower task sets up require config
    bower : {
      all : {
        rjsConfig : 'js/main.js'
      }
    },

    uglify: {
      production: {
        files: [{
          expand: true,
          cwd: 'js',
          src: ['*.js', '!main.js'],
          dest: 'build/js'
        }]
      },
      dev_js: {
        files: {
          "js/modernizr.js" : ['js/vendor/modernizr/modernizr.js'],
          "js/require.js" : ['js/vendor/requirejs/require.js'],
        }
      }
    },

    // Require config
    requirejs : {
      options : {
        name : 'main',
        mainConfigFile : 'js/main.js',
        out : 'js/optimized.min.js'
      },
      dev : {
        options: {
          baseUrl : '<%= config.env.dev.requirejs.baseUrl %>',
        }
      },
      dist : {
        options: {
          name : 'main',
          mainConfigFile : 'js/main.js',
          baseUrl : '<%= config.env.production.requirejs.baseUrl %>',
          out : 'build/js/main.js'
        }
      }
    },

    modernizr: {
      dist: {
        // Path to save out the built file 
        "dest" : "dist/js/modernizr.js",
        // More settings go here 
      }
    },

    // Image min
    imagemin : {
      production : {
        files : [
          {
            expand: true,
            cwd: 'img',
            src: '**/*.{png,jpg,jpeg}',
            dest: 'build/img'
          }
        ]
      }
    },

    // SVG min
    svgmin: {
      production : {
        files: [
          {
            expand: true,
            cwd: 'img',
            src: ['**/*.svg'],
            dest: 'build/img'
          }
        ]
      }
    },

    copy: {
      build: {
        files:[
          {
            expand: true,
            cwd: "./dist",
            src: "*",
            dest: 'build',
          },
          {
            expand: true,
            cwd: './', 
            src: ['*.php', 'templates/*.php', 'img/*.php'],
            dest: 'build'
          },
          {
            expand: true,
            cwd: './css/fonts',
            src: ['*'],
            dest: 'build/css/fonts'
          },
        ]
      }
    },

    // DEPLOY SCRIPTS
    // deploy via rsync
    rsync: {
      options: { 
        args: ["--verbose"],
        exclude: [
          '**/.*',
          'node_modules',
          'scss',
          'js/**/*',
          'config.json',
          'bower.json', 
          'Gruntfile.js', 
          'package.json', 
          'README.md', 
          'config.rb',
          'build',
          'dist'
        ],
        recursive: true
      },
      deployment: {
        options: {
          expand: true,
          cwd: "./",
          src: ["build/*"],
          dest: "../<%= config.info.theme_slug %>-production",
          recursive: true,
          syncDest: false,
          exclude: '<%= rsync.options.exclude %>'
        }
      },
      // staging: {
      //   options: {
      //     src: "./",
      //     dest: "~/var/www/wp-content/themes/ds-new",
      //     host: "root@45.55.168.116",
      //     recursive: true,
      //     syncDest: true
      //   }
      // }
    }
  });




  grunt.registerTask( "build:js", function() {
    var arr = [
      'uglify:dev_js',
    ];
    return grunt.task.run(arr);
  });



  grunt.registerTask( "build:prep", function() {
    var arr = [
      'clean:build', 
      'copy:build'
    ];
    return grunt.task.run(arr);
  });



  grunt.registerTask( "build-theme", function() {
    var arr = [
      'build:prep',
      // 'comments',
      'jshint',
      'sass:production', 
      'requirejs:dist', 
      'imagemin:production',
      'svgmin:production',
      'uglify:production',
      'rsync:deployment',
      'clean:build'
    ];
    return grunt.task.run(arr);
  });

  // Default task
  grunt.registerTask('default', [
    'sass:global', 
    'build:js',
    'watch'
  ]);


  // Run bower install
  grunt.registerTask('bower-install', function() {
    var done = this.async();
    var bower = require('bower').commands;
    bower.install().on('end', function(data) {
      done();
    }).on('data', function(data) {
      console.log(data);
    }).on('error', function(err) {
      console.error(err);
      done();
    });
  });

};
