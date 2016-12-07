<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Controller;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

use Spacycrush\User\User;


/**
 * Contrôleur pour gérer les utilisateurs.
 *
 * @author Steven Bsz
 */
class UsersController extends BaseController
{

	protected $layout = 'users.layout';

	/**
	 * Affiche la page de connexion, ou renvoie l'utilisateur vers le jeu s'il est déjà connecté.
	 *
	 * @return \Illuminate\View\View
	 */
	public function login()
	{
		// User déjà connecté ?
		if (Auth::check()) {
			return Redirect::route('play');
		}

		return View::make('users.login');
	}


	/**
	 * Fait la connexion avec les informations soumises avec le formulaire.
	 * Renvoie l'user vers le jeu si la connexion réussie, ou vers le formulaire en les champs déjà remplis.
	 *
	 */
	public function doLogin()
	{
		$credentials = array(
			'username' => Input::get('username'),
			'password' => Input::get('password')
		);

		if( Auth::attempt($credentials)){
			// Connexion réussis, save last_login column
			$user = User::find(Auth::user()->id);
			$user->last_login = new \DateTime;
			$user->save();

			return Redirect::route('play');
		} else {
			return Redirect::route('login')
				->with('message', 'Your username/password combination was incorrect')
				->withInput();
		}
	}

	/**
	 * Afficher la page d'inscription.
	 *
	 * @return \Illuminate\View\View
	 */
	public function register()
	{
		return View::make('users.register');
	}

	/**
	 * Enregistre l'utilisateur dans la base de données si les informations soumises sont correctes.
	 *
	 */
	public function doRegister()
	{
		$validator = Validator::make(Input::all(), User::$rules);

		if ($validator->passes()) {
			$user = new User;
			$user->username = Input::get('username');
			$user->password = Hash::make(Input::get('password'));
			$user->save();

			return Redirect::route('login')->with('message', 'Thanks for registering!');
		} else {
			return Redirect::to('register')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
		}
	}

	/**
	 * Déconnecte l'utilisateur et renvoie vers le site principal du projet.
	 *
	 */
	public function logout()
	{
		// Remove cached grid before logout
		Cache::forget(Auth::user()->id);

		Auth::logout();
		return Redirect::to('/');
	}

}
