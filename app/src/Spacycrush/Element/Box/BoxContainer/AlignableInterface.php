<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box\BoxContainer;

use Spacycrush\Element\Box\BoxInterface;

/**
 * Représente une grille pouvant contenir des alignements parmi ses cases.
 * C'est à dire si des cases de même type sont alignées verticalement ou horizontalement.
 * Le nombre de cases pouvant créer des alignements peut être variable.
 *
 * @author Steven Bsz
 */
interface AlignableInterface
{

	/**
	 * Détecte si des cases de même type sont alignées, et remplis le tableau des cases alignées utilisé plus tard.
	 *
	 * @return boolean
	 */
	public function hasAlignment();

	/**
	 * Supprime les cases alignées.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\AlignableInterface
	 */
	public function removeAlignedBoxes();

	/**
	 * Vérifie si une case est dans un des alignements
	 *
	 * @param $box L'id de la case
	 *
	 * @return boolean
	 */
	public function isAligned($box);
}
