<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box\BoxContainer;

/**
 * Représente un container pouvant faire bouger ses cases.
 *
 * @author Steven Bsz
 */
interface SwitchableInterface
{
	/**
	 * Vérifie si l'échange est possible.
	 *
	 * @param $boxId1 L'id de la première case - $boxId2 L'id de la seconde case.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\SwitchableInterface|Spacycrush\Exception\NoSwitchPossibleException
	 */
	public function check($boxId1, $boxId2);

	/**
	 * Echange deux cases.
	 *
	 * @param $boxId1 L'id de la première case - $boxId2 L'id de la seconde case.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\SwitchableInterface|Spacycrush\Exception\SwitchUselessException
	 */
	public function doSwitch($boxId1, $boxId2);
}
