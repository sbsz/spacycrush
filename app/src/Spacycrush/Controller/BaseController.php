<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

/**
 * Contrôleur de base par défaut de Laravel4.
 *
 * @author Steven Bsz
 */
class BaseController extends Controller
{

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
