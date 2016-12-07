/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * Gère le score du joueur
 *
 * @author Steven Bsz
 */
services.factory('Score', [function() {
	var Score = {};

	Score.value = 0;

	/**
	 * Set le score
	 */
	Score.setScore = function(value) {
		Score.value = value;
	};

	return Score;

}]);
