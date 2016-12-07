<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Controller;

use Illuminate\Support\Facades\View;

/**
 * Contrôleur pour le site du projet.
 *
 * @author Steven Bsz
 */
class HomeController extends BaseController
{

	/**
	 * Affiche l'index du site
	 */
	public function index()
	{
		return View::make('home.index');
	}

}
