<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Grid;

use Spacycrush\Element\Box\BoxContainer\BoxContainerInterface;

/**
 * Représente une grille
 *
 * @author Steven Bsz
 */
interface GridInterface {

	/**
	 * Retourne l'id
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Retourne le container de cases
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function getContainer();

	/**
	 * Set le container de cases
	 *
	 * @param $container Les cases
	 *
	 * @return void
	 */
	public function setContainer(BoxContainerInterface $container);

}
