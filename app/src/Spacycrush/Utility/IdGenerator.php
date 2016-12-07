<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Utility;

/**
 * Génère un identifiant.
 *
 * @author Steven Bsz
 */
class IdGenerator {

	/**
	 * Génère l'identifiant en fonction de la taille souhaité.
	 *
	 * @param $length La taille souhaité.
	 *
	 * @return string
	 */
	public static function generates($length) {
		return substr( strtoupper( md5(uniqid(rand(),true)) ), 0, $length );
	}

}
