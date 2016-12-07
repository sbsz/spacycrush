<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box\BoxContainer;

/**
 * Représente un container où peut jouer la gravité sur ses éléments.
 *
 * @author Steven Bsz
 */
interface GravityInterface
{
	/**
	 * Fait tomber les cases où leur voisin du bas est vide.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function fallDownBoxes();

}
