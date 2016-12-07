<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Exception;

use \Exception;

/**
 * Exception jetée lorsqu’un échange de deux cases n'est pas possible.
 *
 * @author Steven Bsz
 *
 */
class NoSwitchPossibleException extends Exception {

	public function __construct() {
		$this->message = "";
		$this->code = "noswitchpossible";
	}

}
