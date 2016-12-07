/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * Les directives (attributs/balises HTML personalisés) de l'applicatiion
 *
 * @author Steven Bsz
 */

app

/**
 * Une case
 *
 */
.directive('box', ['Grid', function(Grid) {
	return {
		restrict: 'A',
		scope: true,
		link: function(scope, element, attr) {
			var index = attr.boxIndex;

			// Ajoute des classes pour construire la grille
			if( (index+1) % Grid.size === 0 ){ // Dernier élément sur la droite de la grille
				element.addClass('last');
			}
			if( index <= Math.pow(Grid.size, 2) && index >= (Math.pow(Grid.size, 2) - Grid.size) + 1 ){ // Sur la dernière ligne en bas
				element.addClass('in-last-row');
			}

			// Écoute l'event de suppression
			scope.$on('gridctrl::deletebox-' + attr.id, function() {
				element.transition({ scale: 2, opacity: 0 });
			});

			// Écoute l'event de déplacement (lors d'une chute)
			scope.$on('gridctrl::movebox-' + attr.id, function(event, data) {
				var length = data.to * 68;
				element.transition({ y: length + 'px' });
			});

			// Retirer l'élément du DOM lorsque la case est supprimée de la grille
			scope.$on('$destroy', function() {
				element.remove();
			});

		},
		controller: function($scope, $element) {

			// Écoute l'event de clic sur la case
			$element.on('click', function(event) {
				// Le jeu est bloqué? (chute en cours, error, fin de jeu)
				if( !$scope.app.frozen ){
					event.preventDefault(); // Bloque le comportement par défaut du navigateur lors d'un clic
					$element.addClass('selected');
					Grid.addBoxToSwitch($element, $scope.box); // Ajoute la case pour l'échange
				}
			});
		}
	};
}])

/**
 * Le score
 *
 */
.directive('score', ['Score', function(Score) {
	return {
		restrict: 'A',
		scope: {},
		link: function(scope, element) {
			var h2 = element.find("h2");

			// Écoute le changement du score
			scope.$watch(
				function() { return Score.value;},
				function(newValue, oldValue) {
					if (newValue && oldValue != newValue) { // Nouvelle valeur?

						// Faite un effet d'incrémentation du score
						$({dataScore: h2.text()}).animate({dataScore: newValue}, {
							duration: 1000,
							easing:'swing',
							step: function() {
								h2.text(Math.ceil(this.dataScore));
							}
						});
					}
				}
			);
		}
	};
}])

/**
 * Le décompte
 */
.directive('timer', ['$interval', function($interval) {
	return {
		restrict: 'A',
		scope: {
			onTimeout: '&'
		},
		template: '<div class="timer"><span>{{ timeValue }}</span></div>' +
                   '<div class="outer-bar {{ colorClass }}">' +
                       '<div class="inner-bar" style="height: {{ ((120 - timeValue) * 100) / 120 }}%"></div>' +
                   '</div>',
		link: function(scope) {
			scope.timeValue = 40;
			scope.colorClass = 'top';

			// Décrément le timer toutes les secondes
			var timer = $interval(function() {
				scope.timeValue--;

				// Changer la class si besoin (change la couleur de la jauge)
				if( scope.timeValue < 72 && scope.timeValue > 24 )
					scope.colorClass = 'middle';
				else if (scope.timeValue <= 24)
					scope.colorClass = 'danger';

			}, 1000);

			// On écoute les changements du timer
			scope.$watch('timeValue', function(n, o) {
				if( n !== o && n <= 0) { // Le timer est à 0
					$interval.cancel(timer); // Stop $interval car inutile maintenant et prend des ressources
					scope.onTimeout(); // Execute le chargement du classement, @see DashboardController:endGame()
				}
			});
		}
	};
}])

/**
 * Le modal, peut être utilisé pour autre chose que l'affichage du classement.
 *
 */
.directive('dModal', [function() {
	return {
		restrict: 'A',
		templateUrl: 'templates/endgame',
		replace: true,
		scope: {
			title: '=mdTitle',
			content: '=mdContent',
			validateButton: '=mdValidateButton', // La méthode à exécuter lors de clic sur le bouton de validation
			canceledButton: '=mdCanceledButton' // La méthode à exécuter lors de clic sur le bouton d'annulation
		},
		controller: function($scope) {
			$scope.validate = function() { // Bouton de validation cliquer
				$scope.validateButton.fn(); // Execute la fonction passé
			};
			$scope.canceled = function() {
				$scope.canceledButton.fn();
			};
		}
	};
}])

/**
 * Le loader, lorsque l'application charge.
 */
.directive('spinnerLoading', [function() {
	return {
		restrict: 'A',
		template: '<div class="loading animate-fade"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>'
	};
}]);
