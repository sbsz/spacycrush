/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * Déclare l'application principal
 *
 * @author Steven Bsz
 */

var app = angular.module('app', ['ngAnimate', 'ngResource', 'ngSanitize', 'ngRoute', 'appServices']);

/**
 * Configure l'application
 */
app.config(function($routeProvider) {

	// Configure les routes
	$routeProvider
		.when('/', {
			templateUrl: '/templates/game',
			controller: 'GameController'
		})
		.otherwise({ redirectTo: '/' }); // Si autre chose que '/' est tapé, on revois vers '/'
});
