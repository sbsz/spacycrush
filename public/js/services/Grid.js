/*
 * Ce fichier fait partie du jeu Spacycrush
 */


/**
 * Grid model
 *
 * Représente la grille et ses cases.
 *
 * @author Steven Bsz
 */
services.factory('Grid',
	['ApiService', '$rootScope', '$timeout', '$q', 'Game', 'ErrorsHandler', 'Score',
	function (ApiService, $rootScope, $timeout, $q, Game, ErrorsHandler, Score) {
	var Grid = {};

	Grid.id					= null;
	Grid.size				= 0;
	Grid.boxes				= []; // Contient toutes les cases
	Grid.boxes_aligned		= []; // Contient les cases alignées
	Grid.boxes_to_switch	= []; // Contient deux cases, celles qui l'user a sélectionnées pour l'échange

	/**
	 * Set l'id de la grille
	 *
	 * @param La nouvelle id
	 */
	Grid.setId = function(id) {
		Grid.id = id;
	};

	/**
	 * Set les cases
	 *
	 * @param Les nouvelles cases
	 */
	Grid.setBoxes = function(boxes) {
		Grid.boxes = boxes;
	};

	/**
	 * Initialise la grille en demandant au serveur
	 *
	 * @return void
	 */
	Grid.load = function() {
		var url = '/api/grid/load';

		ApiService.doRequestWithPromise(url).then(function(data) {
			Grid.setBoxes(data.grid.boxes);

			Grid.setId(data.grid.id);
			Grid.size = data.grid.size;

			// On attend avant de stocker les cases à supprimer, pour ne pas déclancher l'évenement trop rapidement
			$timeout(function() {
				if( data.to_remove !== null ){
					Grid.boxes_aligned = data.to_remove; // Cela appelle le watcher sur cet attribut, @see GridController
					$rootScope.$digest();
				}
			}, 800, false);

			Game.gridLoaded = true;
			Game.unfreeze(); // L'user peut jouer

		}, function(result) {
			Game.gridLoaded = false;
			ErrorsHandler.setError("Unable to load game", result.status);
		});

		Score.setScore(0);
	};

	/**
	 * Demande au serveur de faire la suppression des alignements réellement, d'ajouter de nouvelle cases et verifier d'autres alignements.
	 *
	 * @return $q service
	 */
	Grid.nextStep = function() {
		var url = '/api/grid/aftercrush';

		return ApiService.doRequestWithPromise(url);
	};

	/**
	 * Demande au serveur de verifier si un échange est possible, et de le faire si possible.
	 *
	 * @return $q service
	 */
	Grid.switchRequest = function() {
		var url = '/api/grid/switch';

		return ApiService.doPostRequestWithPromise(url, {box1: Grid.boxes_to_switch[0].box.id, box2: Grid.boxes_to_switch[1].box.id});
	};

	/**
	 * Remplace les cases par des nouvelles.
	 *
	 * @param Les nouvelles cases
	 */
	Grid.replaceBoxes = function(newBoxes) {
		for( var i = 0; i < Math.pow(Grid.size, 2); i++) {
			Grid.boxes[i] = newBoxes[i];
		}
	};

	/**
	 * Ajouter une case pour faire l'échange.
	 * Si deux cases sont stockées et voisines, on procède à l'échange visuel toute en demandant au serveur.
	 */
	Grid.addBoxToSwitch = function(element, box) {
		if( Game.frozen ) return;

		var idx = isInToSwitchArray(box.id);

		// Si l'user clic sur une case déjà stocker, on la désélectionne.
		if( idx !== false ){
			Grid.boxes_to_switch.splice(idx, 1);
			element.removeClass('selected');

		// Sinon on stocke la case dans le tableau boxes_to_switch
		} else {
			if( Grid.boxes_to_switch.length > 1 ){ // Seulement deux cases peuvent être sélectionnées
				Grid.boxes_to_switch.shift().element.removeClass('selected'); // On retire la plus ancienne.
			}
			// Stocke la cases.
			Grid.boxes_to_switch.push({
				element: element,
				box: {id: box.id, neighbors: box.neighbors, position: box.position, type: box.type}
			});
		}

		// S'il y a deux cases sélectionnées, on commence à les bouger.
		if( Grid.boxes_to_switch.length === 2 ){
			startMoveBoxes();
		}
	};

	/**
	 * Fait ce qui est nécessaire pour gérer les alignements.
	 */
	Grid.doWhenAlignment = function() {
		Grid.nextStep().then(function(data) { // Demande au serveur la nouvelle grille, les cases ajoutées, les futures alignements

			Score.setScore(data.grid.score); // Met a jour le score

			// Supprime avec une animation les cases alignées
			deleteAlignedBoxes().then(function() {

				$timeout(function(){
					return fallDownBoxesEffect(); // Fait jouer la gravité
				}, 400).then(function (addTimeout){

					// Si aucune case n'est tombée, pas besoin d'attendre la fin de l'animation de chute.
					var valTimeout = addTimeout.length === 0 ? 400 : 800;

					$timeout(function() {

						Grid.replaceBoxes(data.grid.boxes); // Les effets visuels sont terminés, on peut mettre à jour les cases, avec les bons voisins, etc.
						$rootScope.$digest(); // Force Angular à prendre en compte les changements du model.

					}, valTimeout).then(function() {
						// Stocke les nouveaux alignements
						if( data.to_remove !== null )
							Grid.boxes_aligned = data.to_remove; // Cela appelle le watcher sur cet attribut, @see GridController

						// Ou pas
						else
							Grid.boxes_aligned = [];
					});
				});

			});

		}, function(reason) {
			ErrorsHandler.setError(reason.data.message);
		});
	};




	/* ############################################# */
	/* ################## From now ################# */
	/* ####### These methods are inaccessible ######  */
	/* ################ from outside ############### */
	/* ############################################# */


	/* ####### METHODS USED TO SWITCHE BOXES ####### */

	/**
	 * Bouge les cases avec une animation
	 */
	startMoveBoxes = function() {
		Game.freeze();

		// Déplace les deux éléments du DOM avec un effet de déplacement.
		moveElements();

		// Enlève la classe 'selected' sur ces éléments
		Grid.boxes_to_switch[0].element.removeClass('selected');
		Grid.boxes_to_switch[1].element.removeClass('selected');

		// Request API
		Grid.switchRequest().then(function(data) {

			// Échange possible
			if( !data.error ){

				// - Cette partie est juste pour éviter un problème d'affichage lorsque les cases tombent - //
				// Get les deux cases échanger avec leurs voisins et leur position
				var box0 = data.grid.boxes[Grid.boxes_to_switch[1].box.position],
					box1 = data.grid.boxes[Grid.boxes_to_switch[0].box.position]
				;

				// Remplace ces cases dans le container
				Grid.boxes[ Grid.boxes_to_switch[1].box.position ] = {
					id: box0.id,
					neighbors: box0.neighbors,
					position: box0.position,
					type: box0.type
				};

				Grid.boxes[ Grid.boxes_to_switch[0].box.position ] = {
					id: box1.id,
					neighbors: box1.neighbors,
					position: box1.position,
					type: box1.type
				};
				// - End part - //

				// Stocke les cases pour un autre alignement
				if( data.to_remove !== null )
					Grid.boxes_aligned = data.to_remove; // Cela appelle le watcher sur cet attribut, @see GridController

			} else {
				// Échange impossible (inutile), faire le déplacement inverse pour replacer les cases à leur position d'origine.
				moveBack();
			}
		}, function(reason) {
			// Des erreurs pendant la requête (HTTP error)
			ErrorsHandler.setError(reason.data, reason.status);
		}).then(function() {
			// Vide le tableau d'échange
			Grid.boxes_to_switch.length = 0; // Empty box to switch array

			// Débloque le jeu
			Game.unfreeze();
		});

	};

	/**
	 * Déplace les deux éléments du DOM avec un effet de déplacement.
	 */
	moveElements = function() {
		var sign, axis, options, to, idxOtherBox = 1, i;

		for (i = 0; i <= 1; i++) {
			// Récupérer la direction pour le déplacement
			to = getPositionNeig(Grid.boxes_to_switch[i].box, Grid.boxes_to_switch[idxOtherBox].box.id);

			// Les cases sélectionnées ne sont pas voisines
			if( angular.isUndefined(to) ) return;

			options = {};

			// Trouver le signe et l'axe de déplacement
			switch(to) {
				case 'top':
					sign = "-";
					axis = "y";
					break;
				case 'right':
					sign = "+";
					axis = "x";
					break;
				case 'bottom':
					sign = "+";
					axis = "y";
					break;
				case 'left':
					sign = "-";
					axis = "x";
					break;
			}

			options[axis] = sign + '68px';

			// Déplacer l'élément grâce au plugin JQuery transit
			Grid.boxes_to_switch[i].element.transition(options);

			idxOtherBox--;
		}
	};

	/**
	 * Teste si une case est déjà dans le tableau pour l'échange
	 */
	isInToSwitchArray = function(id) {
		for (var i = Grid.boxes_to_switch.length - 1; i >= 0; i--) {
			if( Grid.boxes_to_switch[i].box.id == id ){
				return i;
			}
		}
		return false;
	};

	/**
	 * Déplace les cases vers leur position initiale
	 */
	moveBack = function() {
		Grid.boxes_to_switch[0].element.transition({ x: '0px', y: '0px' });
		Grid.boxes_to_switch[1].element.transition({ x: '0px', y: '0px' });
	};

	/**
	 * Returns where is a box according to the other selected box, to define the way to move
	 */
	getPositionNeig = function(box, idToSearch) {
		if( box.neighbors.top == idToSearch ) return 'top';
		else if( box.neighbors.right == idToSearch ) return 'right';
		else if( box.neighbors.bottom == idToSearch ) return 'bottom';
		else if( box.neighbors.left == idToSearch ) return 'left';
	};


	/* #### METHODES POUR LES ALIGNEMENTS #### */

	/**
	 * Supprime les cases alignées, avec effet d'explosion
	 *
	 * @return $q service
	 */
	deleteAlignedBoxes = function() {
		var deferred = $q.defer();

		angular.forEach(Grid.boxes_aligned, function(box, idBox) {
			$rootScope.$broadcast('gridctrl::deletebox-'+idBox); // Diffuse un event pour exploser une case, en fonction d'id de cette dernière
		});

		deferred.resolve(true);

		return deferred.promise;
	};

	/**
	 * Créer le movement de chute sur les cases dont le voisin du bas est vide
	 *
	 * @return Les cases déplacées
	 */
	fallDownBoxesEffect = function() {

		var toMove = getWhoNeedToBeMoved(Grid.boxes_aligned), // Les cases qui doivent être déplacées
			tmpNeighBox = {},
			toMoveStack = []
		;

		// Parcours lee éléments à déplacer
		for (var i = toMove.length - 1; i >= 0; i--) {
			toMoveStack.push({name: "gridctrl::movebox-" + toMove[i].id, args: {to: toMove[i].row}}); // Stocke le contenu de l'event, pour être diffusé plus tard, tous en même temps

			// On fait de même pour les cases au-dessus de celle sélectionnée pour le déplacement.
			for (var j = 1; j <= toMove[i].topNeig; j++) {
				tmpNeighBox = Grid.boxes[toMove[i].pos - (Grid.size * j)];
				toMoveStack.push({name: "gridctrl::movebox-" + tmpNeighBox.id, args: {to: toMove[i].row}});
			}
		}

		// Diffuse les events
		angular.forEach(toMoveStack, function(moveEvent) {
			$rootScope.$broadcast(moveEvent.name, moveEvent.args);
		});

		return toMove;
	};

	/**
	 * Retourne les cases qui doivent être déplacé, pas toutes, seulement celles dont le voisin du bas est vide.
	 * Le tableau retourner contient les id des cases, ainsi que leur position, le nombre de lignes à se déplacer et le nombre de cases au-dessus.
	 *
	 * @param deletedBoxes Les cases alignées, à supprimer
	 * @return array Un tableau contenant les cases à faire tomber
	 */
	getWhoNeedToBeMoved = function(deletedBoxes) {
		var keysDeleted = Object.keys(deletedBoxes), toMove = [];

		// Parcours les cases supprimées
		angular.forEach(deletedBoxes, function(box) {

			// Si ça n'est pas une des cases supprimées, et le voisin existe (pas sur un bord)
			if( box.neighbors.top !== -1 && keysDeleted.indexOf(box.neighbors.top) === -1 ){
				toMove.push({id: box.neighbors.top, pos: (box.position - Grid.size), row: 0, topNeig: 0});
			}

		});

		// Calcul la profondeur à descendre, c'est à dire le nombre de lignes que la case devra descendre, et le nombre de voisins au-dessus.
		angular.forEach(toMove, function(data) {
			var count = 0;
			while(
				( data.pos + (Grid.size * (count+1)) ) < Math.pow(Grid.size, 2) // On ne depasse pas la grille
			){
				if( keysDeleted.indexOf( Grid.boxes[ data.pos + (Grid.size * (count+1)) ].id ) !== -1 ){ // Si la case n'est pas une case supprimer
					data.row++;
				}
				count++;
			}

			// Si on est pas au bord et ce n'est pas une case supprimée
			if( Grid.boxes[data.pos].neighbors.top !== -1 && keysDeleted.indexOf(Grid.boxes[data.pos].neighbors.top) === -1 ){
				data.topNeig++;

				while( data.pos - (Grid.size * (data.topNeig+1)) >= 0 ) { // On est dans la grille
					data.topNeig++;
				}
			}
		});

		return toMove;
	};

	return Grid;
}]);
