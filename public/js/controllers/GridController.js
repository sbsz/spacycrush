/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * @author Steven Bsz
 */
app.controller('GridController', function($scope, $timeout, Grid) {

	// Bind le model Grid dans le scope
	$scope.grid = Grid;

	// Charge la grille
	Grid.load();

	// Lorsqu'il y a des cases à faire disparaitre
	$scope.$watch('grid.boxes_aligned', function(newVal, oldVal) {
		if( newVal !== oldVal && newVal.length !== 0 ){
			$scope.app.freeze();

			Grid.doWhenAlignment();

		} else $scope.app.unfreeze();
	});

})
.$inject = ['$scope', '$timeout', 'Grid'];
