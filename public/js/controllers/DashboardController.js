/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * @author Steven Bsz
 */
app.controller('DashboardController', function($scope, Grid, Game) {
	$scope.modal = {};
	$scope.modal.hidden = true;

	$scope.endGame = function() {

		$scope.$watch(
			function() {
				return Grid.boxes_aligned.length;
			}, function(n) {

				// Attend que toutes les explosions soient terminées
				if( n === 0 ){
					$scope.app.freeze(); // Freeze le jeu

					Game.loadRanking().then(function(data) {
						$scope.modal.title = data.title;
						$scope.modal.content = data.content;
						$scope.modal.validate = {title: 'Play again !', fn: function() {
							return $scope.playAgain();
						}};
						$scope.modal.canceled = {title: 'Exit (logout)', fn: function() {
							return $scope.exit();
						}};
						$scope.modal.hidden = false;

					});

				}
			}
		);

	};

})
.$inject = ['$scope', 'Grid', 'Game'];
