<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Utility;

use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Créer un tableau à partir d'un objet
 *
 * @author Steven Bsz
 */
class ArrayMake
{

	/**
	 * Génère le tableau en appelant la méthode toArray de l'objet.
	 *
	 * @param $data L'objet à transformer.
	 * @return array
	 */
	public static function make($data)
	{
		return array_map(function($value)
		{
			return $value instanceof ArrayableInterface ? $value->toArray() : $value;

		}, $data);
	}

}
