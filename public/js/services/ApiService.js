/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * Api service
 *
 * Ce service fait les requêtes AJAX à Laravel
 * Methodes supportées: GET et POST
 * Les méthodes retournent une promise
 *
 * @author Steven Bsz
 */
services.service('ApiService', ['$http', '$q', function($http, $q) {

	/**
	 * Fait une requête GET et retourne une promise.
	 */
	this.doRequestWithPromise = function(url) {
		var deferred = $q.defer();

		$http.get(url).success(function(data) {
			deferred.resolve(data);
		}).
		error(function(data, status){
			deferred.reject({data: data, status: status});
		});

		return deferred.promise;
	};

	/**
	 * Fait une requête POST et retourne une promise.
	 */
	this.doPostRequestWithPromise = function(url, data) {
		var deferred = $q.defer();

		$http.post(url, data).success(function(data) {
			deferred.resolve(data);
		}).
		error(function(data, status){
			deferred.reject({data: data, status: status});
		});

		return deferred.promise;
	};

}]);
