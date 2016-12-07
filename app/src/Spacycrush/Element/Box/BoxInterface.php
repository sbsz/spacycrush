<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box;

/**
 * Représente une cases.
 *
 * @author Steven Bsz
 */
interface BoxInterface {

	/**
	 * Retourne l'id
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Retourne le type
	 *
	 * @return integer
	 */
	public function getType();

	/**
	 * Retourne la position
	 *
	 * @return integer
	 */
	public function getPosition();

	/**
	 * Set la position
	 *
	 * @param $position La nouvelle position
	 *
	 * @return void
	 */
	public function setPosition($position);

	/**
	 * Set un voisin
	 *
	 * @param $where L'endroit où se situe le voisin - $who L'id du voisin
	 *
	 * @return void
	 */
	public function setNeighbor($where, $who);

	/**
	 * Return l'id d'un voisin
	 *
	 * @param $where La position du voisin en fonction de la case actuelle.
	 *
	 * @return string
	 */
	public function getNeighbor($where);

	/**
	 * Retourne tous les voisins
	 *
	 * @return array
	 */
	public function getNeighbors();

	/**
	 * Initialise les voisins
	 *
	 * @param $width La largeur du container, pour initialiser les bords.
	 *
	 * @return void
	 */
	public function initNeighbors($width);
}
