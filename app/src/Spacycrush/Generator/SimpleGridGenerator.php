<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Generator;

use Spacycrush\Generator\GeneratorInterface;
use Spacycrush\Element\Grid\Grid;
use Spacycrush\Element\Box\SimpleBox;
use Spacycrush\Element\Box\BoxContainer\BoxContainer;

/**
 * Cette classe est capable de générer une grille avec ses cases (TODO baser la génération sur un fichier de configuration)
 *
 * @author Steven Bsz
 */
class SimpleGridGenerator implements GeneratorInterface
{
	/**
	 * Le niveau actuelle du joueur.
	 *
	 * @var integer
	 */
	private $level;

	/**
	 * La largeur de la grille
	 *
	 * @var integer
	 */
	private $width;

	/**
	 * Le nombre minimum de cases de même type pouvant créer un alignement.
	 *
	 * @var integer
	 */
	private $min;

	/**
	 * Initialise le générateur avec des options si définies.
	 *
	 * @param $options Les options
	 *		  level			: le niveau de l'utilisateur, pour basé la génération.
	 *		  size 			: La taille de la grille à générer.
	 *		  minAligned 	: Le nombre minimum de cases de même type pouvant créer un alignement.
	 *
	 * @return void
	 */
	public function __construct($options = array())
	{
		$this->level 	= (isset($options['level']) && $options['level'] > 0) ? $options['level'] : 1;
		$this->min 		= (isset($options['minAligned']) && $options['minAligned'] > 0) ? $options['minAligned'] : 3;
		$this->width 	= (isset($options['size']) && $options['size'] > 0) ? $options['size'] : 9;
	}

	/**
	 * Génère la grille en fonction des options.
	 *
	 * @return Spacycrush\Element\Grid\GridInterface
	 */
	public function generates()
	{
		// TODO - Generation avec $this->level et levels.yml

		$containerOptions = array();

		if( !is_null($this->width) )
			$containerOptions['width'] = $this->width;
		if( !is_null($this->min) )
			$containerOptions['min'] = $this->min;

		$container = new BoxContainer(null, $containerOptions);

		// En attendant levels.yml, on remplit aléatoirement le container de simples cases.
		for ($i=0; $i < pow($this->width, 2); $i++) {
			$container->add(new SimpleBox(mt_rand(1, 5), $i));
		}

		$container->updateNeighbors();

		$grid = new Grid($container);

		return $grid;
	}

	/**
	 * Génère de nouvelles cases
	 *
	 * @param $count The number of boxes to generate
	 *
	 * @return array[Spacycrush\Element\Box\BoxInterface]
	 */
	public function generateNewBoxes($count)
	{
		/* TODO - utiliser levels.yml */
		$newsBoxes = array();

		for ($i=0; $i < $count; $i++) {
			$newsBoxes[] = new SimpleBox(mt_rand(1, 5), 0);
		}

		return $newsBoxes;
	}

}
