<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box\BoxContainer;

use Spacycrush\Element\Box\BoxInterface;

/**
 * Représente un ensemble de cases.
 *
 * @author Steven Bsz
 */
interface BoxContainerInterface
{
	/**
	 * Ajoute une case dans le container.
	 *
	 * @param Spacycrush\Element\Box\BoxInterface $box La case à ajouter
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function add(BoxInterface $box);

	/**
	 * Ajoute plusieurs cases dans le container.
	 *
	 * @param array $boxes Un tableau de cases
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function fillUp($boxes);

	/**
	 * Récupère une boite du container, en fonction d'un attribut de la classe BoxInterface et de sa valeur.
	 *
	 * @param string $attr Un nom d'attribut d'une classe implémentant BoxInterface - mixed $value La valeur que doit avoir l'attribut.
	 *
	 * @return Spacycrush\Element\Box\BoxInterface|null
	 */
	public function findBy($attr, $value);

	/**
	 * Retourne toute les cases
	 *
	 * @return array[Spacycrush\Element\Box\BoxInterface]|null
	 */
	public function all();

	/**
	 * Trie le container en fonction de la position des cases.
	 *
	 * @return void
	 */
	public function sortByPosition();
}
