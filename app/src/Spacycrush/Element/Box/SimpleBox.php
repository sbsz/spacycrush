<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box;

use Spacycrush\Element\Box\BoxInterface;
use Spacycrush\Utility\IdGenerator;

use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Une simple case sans comportement particulier.
 *
 * @author Steven Bsz
 */
class SimpleBox implements BoxInterface, ArrayableInterface
{
	/**
	 * L'identifiant de la case, unique.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Le type de la case.
	 *
	 * @var integer
	 */
	private $type;

	/**
	 * La position de la case sur le grille.
	 *
	 * @var integer
	 */
	private $position;

	/**
	 * Les voisins, représenté comme suit :
	 * 	[
	 *		top 	=> idVoisin,
	 *		right 	=> idVoisin,
	 *		bottom 	=> idVoisin,
	 *		left 	=> idVoisin
	 * 	]
	 *
	 * @var array
	 */
	private $neighbors;

	/**
	 * Le nombre de point que donne la case
	 *
	 * @var integer
	 */
	private $points;

	public function __construct($type, $position) {
		$this->id = IdGenerator::generates(20);
		$this->type = $type;
		$this->position = $position;
		$this->neighbors = array();
		$this->points = 50;
	}

	/**
	 * Retourne l'id
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Retourne le type
	 *
	 * @return integer
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set le type
	 *
	 * @param $type Le nouveau type
	 *
	 * @return void
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Retourne la position
	 *
	 * @return integer
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * Set la position
	 *
	 * @param $position La nouvelle position
	 *
	 * @return void
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}

	/**
	 * Set un voisin
	 *
	 * @param $where L'endroit où se situe le voisin - $who L'id du voisin
	 *
	 * @return void
	 */
	public function setNeighbor($where, $who)
	{
		$this->neighbors[$where] = $who;
	}

	/**
	 * Return l'id d'un voisin
	 *
	 * @param $where La position du voisin en fonction de la case actuelle.
	 *
	 * @return string
	 */
	public function getNeighbor($where)
	{
		return $this->neighbors[$where];
	}

	/**
	 * Retourne tous les voisins
	 *
	 * @return array
	 */
	public function getNeighbors()
	{
		return $this->neighbors;
	}

	/**
	 * Retourne les points
	 *
	 * @return array
	 */
	public function getPoints()
	{
		return $this->points;
	}

	/**
	 * Initialise les voisins
	 *
	 * @param $width La largeur du container, pour initialiser les bords.
	 *
	 * @return void
	 */
	public function initNeighbors($width)
	{
		$pos = $this->position;
		if( $pos % $width === 0 ) { // A droite
			$this->neighbors['left'] = -1;
		} else $this->neighbors['left'] = null;

		if( $pos % $width === $width-1 ){ // Gauche
			$this->neighbors['right'] = -1;
		} else $this->neighbors['right'] = null;

		if( $pos >= 0 && $pos < $width ){ // Haut
			$this->neighbors['top'] = -1;
		} else $this->neighbors['top'] = null;

		if( $pos >= (pow($width, 2) - $width) && $pos < (pow($width, 2)) ){ // Bas
			$this->neighbors['bottom'] = -1;
		} else $this->neighbors['bottom'] = null;
	}

	/**
	 * Retourne la case sous forme de tableau.
	 *
	 * @return array
	 */
	public function toArray()
	{

		return array(
			'id' 	=> $this->id,
			'type' 	=> $this->type,
			'position' => $this->position,
			'neighbors' => $this->neighbors
		);
	}
}
