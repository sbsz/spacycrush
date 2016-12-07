<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Generator;

/**
 * Représente un générateur.
 *
 * @author Steven Bsz
 */
interface GeneratorInterface
{
	/**
	 * Génère
	 *
	 * @return mixed
	 */
	public function generates();
}
