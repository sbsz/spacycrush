module.exports = function(grunt) {

	var dir = 'public/';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		/* JS */
		jshint: {
			all: [
				'Gruntfile.js',
				dir + 'js/**/*.js',
				'!' + dir + 'js/app.min.js'
			]
		},

		uglify: {
			prod: {
				options: {
					mangle: false
				},
				src: [
					dir+'bower_components/jquery/dist/jquery.min.js',
					dir+'bower_components/jquery.transit/jquery.transit.js',
					dir+'bower_components/angular/angular.min.js',
					dir+'bower_components/angular-route/angular-route.min.js',
					dir+'bower_components/angular-animate/angular-animate.min.js',
					dir+'bower_components/angular-resource/angular-resource.min.js',
					dir+'bower_components/angular-sanitize/angular-sanitize.min.js',
					dir+'js/services/services.js',
					dir+'js/services/*.js',
					dir+'js/app.js',
					dir+'js/directives.js',
					dir+'js/controllers/*.js',
					'!'+dir+'js/app.min.js',
				],
				dest: dir+'js/app.min.js'
			},
			dev: {
				options: {
					mangle: false,
					beautify: true
				},
				src: [
					dir+'js/services/services.js',
					dir+'js/services/*.js',
					dir+'js/app.js',
					dir+'js/directives.js',
					dir+'js/controllers/*.js',
					'!'+dir+'js/app.min.js',
				],
				dest: dir+'js/app.min.js'
			}
		},

		/* SCSS */
		compass: {
			dist: {
				options: {
					config: dir + 'config.rb'
				}
			}
		},
		concat: {
			dist: {
				src: [
					dir+'bower_components/animate.css/animate.min.css',
					dir+'css/app.css'
				],
				dest: dir+'css/app.min.css'
			}
		},

		/* WATCH */
		watch: {
			scripts: {
				files: [
					'Gruntfile.js',
					dir+'js/**/*.js',
					'!'+dir+'js/app.min.js'
				],
				tasks: ['jshint', 'uglify'],
			},
			scss: {
				files: dir + 'sass/**/*.scss',
				tasks: ['compass', 'concat']
			}
		}

	});

	/* Load tasks */
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-jshint');

	grunt.registerTask('default', ['compass', 'concat', 'jshint', 'uglify:dev', 'watch']);
	grunt.registerTask('production', ['compass', 'concat', 'jshint', 'uglify:prod']);
};