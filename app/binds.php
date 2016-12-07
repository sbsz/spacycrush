<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * IoC bindings (dependency injection)
 * Dit à l'IoC quelle dépendance il doit utiliser pour un objet.
 *
 * @author Steven Bsz
 */

App::bind('Spacycrush\Controller\GameController', function($app) {
	$controller = new Spacycrush\Controller\GameController(
		new Spacycrush\Cache\CacheFile,
		new Spacycrush\Generator\SimpleGridGenerator
	);
	return $controller;
});
