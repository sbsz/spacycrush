<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Exception;

use \Exception;

/**
 * Exception jetée lorsqu’un échange de cases ne créé pas d'alignement.
 *
 * @author Steven Bsz
 *
 */
class SwitchUselessException extends Exception {

	public function __construct() {
		$this->message = "";
		$this->code = "switchuseless";
	}

}
