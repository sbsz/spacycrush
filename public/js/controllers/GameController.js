/*
 * Ce fichier fait partie du jeu Spacycrush
 */


/**
 * @author Steven Bsz
 */
app.controller('GameController', function($scope, Game, ErrorsHandler) {

	$scope.app = Game;

	$scope.app.error = ErrorsHandler;

	$scope.playAgain = function() {
		window.location.reload();
	};

	$scope.exit = function() {
		window.location = '/logout';
	};

	/* Events */
	$scope.$on('boxesswitchdirective::error', function() {
		ErrorsHandler.setError("An error has occured, please reload the game !");
	});
})
.$inject = ['$scope', 'Game', 'ErrorsHandler'];
