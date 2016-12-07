/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * Gére les erreurs de l'application
 * S'il y a une erreur, le joueur en est informé.
 *
 * @author Steven Bsz
 */
services.factory('ErrorsHandler', ['Game', function(Game) {
	var ErrorsHandler = {
		message: '',
		code: 0
	};

	// Ajoute une erreur
	ErrorsHandler.setError = function(message, code) {
		ErrorsHandler.message = message;
		ErrorsHandler.code = code || 0;
		Game.freeze();
	};

	return ErrorsHandler;

}]);
