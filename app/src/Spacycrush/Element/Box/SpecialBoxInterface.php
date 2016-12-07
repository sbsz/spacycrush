<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box;

use Spacycrush\Element\Box\BoxContainer\BoxContainerInterface;

/**
 * Représente une case avec un comportement spécial.
 *
 * @author Steven Bsz
 */
interface SpecialBoxInterface
{

	/**
	 * Exécute son comportement sur le container de cases.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function doSpecialCrush(BoxContainerInterface $boxes);

}
