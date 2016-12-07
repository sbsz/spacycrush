<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Grid;

use Spacycrush\Element\Grid\GridInterface;
use Spacycrush\Element\Box\BoxContainer\BoxContainerInterface;
use Spacycrush\Utility\IdGenerator;
use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Représente un grille de jeu
 *
 * @author Steven Bsz
 */
class Grid implements GridInterface, ArrayableInterface
{
	/**
	 * L'id
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Le container de cases
	 *
	 * @var Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	private $container;

	public function __construct(BoxContainerInterface $container) {
		$this->id = IdGenerator::generates(30);
		$this->container = $container;
	}

	/**
	 * Redéfinition de la méthode clone.
	 *
	 */
	public function __clone()
	{
		$this->container = clone $this->container;
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
	 * Retourne le container de cases
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Set le container de cases
	 *
	 * @param $container Les cases
	 *
	 * @return void
	 */
	public function setContainer(BoxContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Retourne les cases alignées
	 *
	 * @return array
	 */
	public function getAlignedBoxes()
	{
		return $this->container->getAlignedBoxes();
	}

	/**
	 * Execute les fontions necessaire lors d'un alignement :
	 *		Supprimer les cases alignées du container
	 *		Fait tomber celles sans voisin en bas
	 *		Ajoute de nouvelle cases
	 *
	 * @param $newBoxes Les cases à ajouter
	 *
	 * @return Spacycrush\Element\Grid\Grid
	 */
	public function doStuffOnAlignedBoxes($newBoxes)
	{
		$this->container
			 ->removeAlignedBoxes()
			 ->fallDownBoxes()
			 ->fillUp($newBoxes)
			 ->updateNeighbors()
			 ->calculateScore()
			 ->emptyAligned();

		return $this;
	}

	/**
	 * Verifie les alignements dans la grille
	 *
	 * @return array|null
	 */
	public function checkAlignement()
	{
		if( $this->container->hasAlignment() )
			return $this->container->getAlignedBoxesToArray();

		return null;
	}

	/**
	 * Retourne la grille et son contenu sous forme de tableau
	 *
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'id' 	=> $this->id,
			'size' 	=> $this->container->getWidth(),
			'boxes' => $this->container->toArray(),
			'score' => $this->container->getScore()
		);
	}
}
