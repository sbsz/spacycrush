<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

/**
 * Les routes de l'application.
 *
 * @author Steven Bsz
 */

/* Main website */
Route::get('/', ['as' => 'home', 'uses' => 'Spacycrush\Controller\HomeController@index']);


/* Users routes */
Route::get('login', ['as' => 'login', 'uses' => 'Spacycrush\Controller\UsersController@login']);
Route::post('login', ['as' => 'postLogin', 'uses' => 'Spacycrush\Controller\UsersController@doLogin']);

Route::get('register', ['as' => 'register', 'uses' => 'Spacycrush\Controller\UsersController@register']);
Route::post('register', ['as' => 'postRegister', 'uses' => 'Spacycrush\Controller\UsersController@doRegister']);

Route::get('logout', ['as' => 'logout', 'uses' => 'Spacycrush\Controller\UsersController@logout']);


/* Game */
Route::get('play', ['before' => 'auth', 'as' => 'play', 'uses' => 'Spacycrush\Controller\GameController@play']);


/* Api */
Route::group(array('before' => 'auth', 'prefix' => 'api'), function() {

	Route::get('grid/load', 'Spacycrush\Controller\GameController@init');
	Route::post('grid/switch', 'Spacycrush\Controller\GameController@switchBoxes');
	Route::get('grid/aftercrush', 'Spacycrush\Controller\GameController@afterCrush');
	Route::get('loadranking', 'Spacycrush\Controller\GameController@loadRanking');

});


/* Views */
Route::group(array('before' => 'auth', 'prefix' => 'templates'), function() {
	Route::get('game', function() {
		return View::make('ng_templates.game');
	});
	Route::get('endgame', function() {
		return View::make('ng_templates.modal');
	});
});
