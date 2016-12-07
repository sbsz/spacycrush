<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Exception;

use \Exception;

/**
 * Exception jetée lorsqu’une grille n'est pas trouvée dans le cache.
 *
 * @author Steven Bsz
 *
 */
class CacheFileNotFoundException extends Exception {

	public function __construct() {
		$this->message 	= "Session not found";
		$this->code 	= "nosession";
	}
}
