/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * Game model
 *
 * @author Steven Bsz
 */
services.factory('Game', ['ApiService', function(ApiService) {
	var Game = {
		gridLoaded: false,
		dashLoaded: true,
		frozen: true // Si true, l'user ne peut pas interagir avec la grille.
	};

	/**
	 * Retourne l'état de l'application.
	 *
	 * @return boolean
	 */
	Game.ready = function() {
		return Game.gridLoaded && Game.dashLoaded;
	};

	/**
	 * Bloque le jeu, le joueur ne peut plus manipuler la grille.
	 *
	 * @return void
	 */
	Game.freeze = function() {
		Game.frozen = true;
	};

	/**
	 * Debloque le jeu, le joueur peut manipuler la grille.
	 *
	 * @return void
	 */
	Game.unfreeze = function() {
		Game.frozen = false;
	};

	/**
	 * Charge les scores.
	 *
	 * @return $q service
	 */
	Game.loadRanking = function() {
		var url = '/api/loadranking';

		return ApiService.doRequestWithPromise(url);
	};

	return Game;

}]);
