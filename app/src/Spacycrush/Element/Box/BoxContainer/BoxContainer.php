<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Element\Box\BoxContainer;

use Spacycrush\Element\Box\BoxContainer\AlignableInterface;
use Spacycrush\Element\Box\BoxContainer\SwitchableInterface;
use Spacycrush\Element\Box\BoxContainer\GravityInterface;
use Spacycrush\Element\Box\BoxInterface;
use Spacycrush\Exception\SwitchUselessException;
use Spacycrush\Exception\NoSwitchPossibleException;

use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Represent a collection of boxes
 *
 * @author Steven Bsz
 */
class BoxContainer implements BoxContainerInterface, AlignableInterface, SwitchableInterface, GravityInterface, ArrayableInterface
{

	/**
	 * Contient les cases.
	 *
	 * @var array
	 */
	private $boxes;

	/**
	 * Le nombre de cases nécessaire pouvant créer un alignement.
	 *
	 * @var integer
	 */
	private $min;

	/**
	 * La largeur du container, le container est représenté comme un carré.
	 *
	 * @var integer
	 */
	private $width;

	/**
	 * Contient les cases alignées, identifiées par leur id.
	 *
	 * @var array
	 */
	private $aligned;

	/**
	 * Le score actuel du joueur.
	 *
	 * @var integer
	 */
	private $score;


	public function __construct($boxes = array(), $options = array())
	{
		$this->boxes = is_null($boxes) ? array() : $boxes;
		$this->min = isset($options['min']) ? intval($options['min']) : 3;
		$this->width = (isset($options['width']) && $options['width'] > 0) ? intval($options['width']) : 9;
		$this->aligned = array();
		$this->score = 0;
	}

	/**
	 * Redéfinition de la méthode clone.
	 *
	 */
	public function __clone()
	{
		foreach ($this->boxes as $key => $box) {
			$this->boxes[$key] = clone $box;
		}
	}

	/**
	 * Retourne toute les cases
	 *
	 * @return array[Spacycrush\Element\Box\BoxInterface]|null
	 */
	public function all()
	{
		return $this->boxes;
	}

	/**
	 * Retourne l'attribut min.
	 *
	 * @return integer
	 */
	public function getMin()
	{
		return $this->min;
	}

	/**
	 * Retourne l'attribut aligned
	 *
	 * @return array
	 */
	public function getAlignedBoxes()
	{
		return $this->aligned;
	}

	/**
	 * Retourne l'attribut aligned sous forme de tableau, c’est-à-dire que les objets contenus dans celui-ci sont convertis en tableau.
	 *
	 * @return array
	 */
	public function getAlignedBoxesToArray()
	{
		$aligned = $this->aligned;
		foreach ($aligned as $idBox => $box) {
			$aligned[$idBox] = $box->toArray();
		}
		return $aligned;
	}

	/**
	 * Retourne l'attribut width
	 *
	 * @return array
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * Retourne l'attribut score
	 */
	public function getScore()
	{
		return $this->score;
	}

	/**
	 * Ajoute une case dans le container.
	 *
	 * @param Spacycrush\Element\Box\BoxInterface $box La case à ajouter
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function add(BoxInterface $box)
	{
		$this->boxes[] = $box;

		return $this;
	}

	/**
	 * Ajoute plusieurs cases dans le container.
	 *
	 * @param array $boxes Un tableau de cases
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function fillUp($boxes)
	{
		if( !is_array($boxes) ) return $this;

		foreach ($this->boxes as $pos => $box) {
			if( $box === 'empty' ){
				$this->boxes[$pos] = array_shift($boxes);
				$this->boxes[$pos]->setPosition($pos);
			}
		}

		return $this;
	}

	/**
	 * Récupère une boite du container, en fonction d'un attribut de la classe BoxInterface et de sa valeur.
	 *
	 * @param string $attr Un nom d'attribut d'une classe implémentant BoxInterface - mixed $value La valeur que doit avoir l'attribut.
	 *
	 * @return Spacycrush\Element\Box\BoxInterface|null
	 */
	public function findBy($attr, $value)
	{
		foreach ($this->boxes as $key => $box) {
			if( $box !== 'empty' && $box->{'get' . ucfirst($attr)}() == $value ){
				return $box;
			}
		}

		return null;
	}


	/**
	 * Trie le container en fonction de la position des cases.
	 *
	 * @return void
	 */
	public function sortByPosition()
	{
		$sorted = array(); $result = array();

		foreach ($this->boxes as $key => $box)
		{
		    $sorted[$key] = $box->getPosition();
		}

		asort($sorted);

		foreach (array_keys($sorted) as $key)
		{
		    $sorted[$key] = $this->boxes[$key];
		}

		/* Reconstruction of key */
		foreach ($sorted as $box) {
			$results[] = $box;
		}

		$this->boxes = $results;

		return $this;
	}

	/**
	 * Tri un tableau contenant des cases en fonction de leur position.
	 *
	 * @param Le tableau à trier
	 *
	 * @return Un tableau trié
	 */
	private function sortArrayByPosition($array) {
		$sorted = array(); $result = array();

		foreach ($array as $key => $box)
		{
		    $sorted[$key] = $box->getPosition();
		}

		asort($sorted);

		foreach (array_keys($sorted) as $key)
		{
		    $sorted[$key] = $array[$key];
		}

		return $sorted;
	}

	/**
	 * Détecte si des cases de même type sont alignées, et remplis le tableau des cases alignées utilisé plus tard.
	 *
	 * @return boolean
	 */
	public function hasAlignment()
	{
		// Parcours l'ensemble des cases
		for ($i=0; $i < pow($this->width, 2); $i++) {
			if( $this->boxes[$i]->getNeighbor("right") !== -1 ) { // La cases n'est pas sur le bord droit
				$countRightNeig = $this->checkBoxNeighbor($this->boxes[$i], 'right'); // Compte le nombre de voisins de même type consécutifs.
				if( $countRightNeig >= ($this->min - 1) ){ // Le nombre de cases alignées est plus grand ou égal que le nombre requis
					// Ajoute cette case et les suivante dans le tableau des alignées
					for ($j=0; $j <= $countRightNeig; $j++) {
						$this->aligned[$this->boxes[$i+$j]->getId()] = $this->boxes[$i+$j];
					}
				}
			}

			if( $this->boxes[$i]->getNeighbor('bottom') !== -1 ) { // La cases n'est pas sur le bord bas
				$countBottomNeig = $this->checkBoxNeighbor($this->boxes[$i], 'bottom');
				if( $countBottomNeig >= ($this->min - 1) ){
					$keyBox = $i;

					$this->aligned[$this->boxes[$keyBox]->getId()] = $this->boxes[$keyBox]; // Ajoute la première case
					while ( $countBottomNeig > 0 ) { // et les suivante en fonction de la profondeur de voisins consécutifs
						$keyBox += $this->width;
						$this->aligned[$this->boxes[$keyBox]->getId()] = $this->boxes[$keyBox];
						$countBottomNeig--;
					}
				}
			}
		}

		$this->aligned = $this->sortArrayByPosition($this->aligned); // On trie le tableau par position des cases

		return !empty($this->aligned);
	}

	/**
	 * Supprime les cases alignées.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\AlignableInterface
	 */
	public function removeAlignedBoxes()
	{
		foreach ($this->aligned as $idBox => $box) {
			$this->boxes[$box->getPosition()] = 'empty';
		}

		return $this;
	}

	/**
	 * Vérifie si une case est dans un des alignements
	 *
	 * @param $box L'id de la case
	 *
	 * @return boolean
	 */
	public function isAligned($box)
	{
		if( in_array($box, array_keys($this->aligned)) ){
			return true;
		}

		return false;
	}

	/**
	 * Fonction récursive vérifiant si les voisins d'une case ont le même type que celle donnée, et retourne le nombre de voisins de même type consécutifs.
	 *
	 * @param $box La case à vérifier - $way La direction où doit s'effectuer la vérification - $n Le nombre de cases alignées.
	 *
	 * @return integer
	 */
	protected function checkBoxNeighbor(BoxInterface $box, $way, $n = 0)
	{
		$neig = $this->findBy('id', $box->getNeighbor($way)); // Récupère la case voisine

		if( $neig && $box->getType() === $neig->getType() ){ // Il y a un voisin et de même type
			return $this->checkBoxNeighbor($neig, $way, $n+1);
		} else {
			return $n;
		}
	}

	/**
	 * Met à jour toutes les cases du container.
	 *
	 * @return Spacycrush\Element\BoxContainer\BoxContainerInterface
	 */
	public function updateNeighbors() {
		// Set les bord
		foreach ($this->boxes as $pos => $box) {
			$this->boxes[$pos]->initNeighbors($this->width);
		}

		// Init les voisins
		foreach ($this->boxes as $key => $box) {
			if( $box->getNeighbor('top') !== -1 ) {
				$this->boxes[$key]->setNeighbor('top', $this->boxes[$key-$this->width]->getId()); // Met l'id de la case située en dessous de l'actuelle
			}
			if( $box->getNeighbor('right') !== -1 ) {
				$this->boxes[$key]->setNeighbor('right', $this->boxes[$key+1]->getId()); // Met l'id de la case située à droite de l'actuelle
			}
			if( $box->getNeighbor('bottom') !== -1 ) {
				$this->boxes[$key]->setNeighbor('bottom', $this->boxes[$key+$this->width]->getId()); // Met l'id de la case située en bas de l'actuelle
			}
			if( $box->getNeighbor('left') !== -1 ) {
				$this->boxes[$key]->setNeighbor('left', $this->boxes[$key-1]->getId()); // Met l'id de la case située à gauche de l'actuelle
			}
		}

		return $this;
	}

	/**
	 * Vérifie si l'échange est possible.
	 *
	 * @param $boxId1 L'id de la première case - $boxId2 L'id de la seconde case.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\SwitchableInterface|Spacycrush\Exception\NoSwitchPossibleException
	 */
	public function check($boxId1, $boxId2)
	{
		$box1 = $this->findBy('id', $boxId1);
		$box2 = $this->findBy('id', $boxId2);

		$toReturn[$box1->getId()] = array_search($box1->getId(), $box2->getNeighbors()); // Test si b1 est voisin de b2
		$toReturn[$box2->getId()] = array_search($box2->getId(), $box1->getNeighbors()); // Test si b2 est voisin de b1

		if( !in_array(false, $toReturn) )
			return $this;
		else
			throw new NoSwitchPossibleException();
	}

	/**
	 * Echange deux cases.
	 *
	 * @param $boxId1 L'id de la première case - $boxId2 L'id de la seconde case.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\SwitchableInterface|Spacycrush\Exception\SwitchUselessException
	 */
	public function doSwitch($boxId1, $boxId2)
	{
		// Récupère les cases sélectionnées, et on garde une copie de leur position actuelle.
		$box1 = $this->findBy('id', $boxId1); $oldPositionBox1 = $box1->getPosition();
		$box2 = $this->findBy('id', $boxId2); $oldPositionBox2 = $box2->getPosition();

		// Échange attribut position
		$box1->setPosition($oldPositionBox2);
		$box2->setPosition($oldPositionBox1);

		$this->sortByPosition();

		// Reconstruit les voisins (pas trop optimal ^^)
		$this->updateNeighbors();

		if( ($this->hasAlignment() && !$this->isAligned($boxId1) && !$this->isAligned($boxId2) ) || !$this->hasAlignment() ){
			throw new SwitchUselessException();
		} else {
			return $this;
		}
	}

	/**
	 * Fait tomber les cases où leur voisin du bas est vide.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function fallDownBoxes()
	{
		// Fait "tomber" les cases
		for ($i = pow($this->width, 2) - 1; $i >= 0; $i--) {
			if( $this->boxes[$i] !== 'empty' ){
				$count = 0;

				// Tant qu'en dessous c'est vide, on peut descendre
				while(
					isset($this->boxes[$i + ($this->width * ($count+1) )]) &&
					$this->boxes[$i + ($this->width * ($count+1) )] === 'empty'
				){
					$count++;
				}

				// On attribut la nouvelle position
				if( $count > 0 && isset($this->boxes[$i + ($this->width * $count)]) ){
					$newPosition = $i + ($this->width * $count);
					$currentBox = $this->findBy('position', $i);

					$currentBox->setPosition($newPosition);
					$currentBox->initNeighbors($this->width);
					$this->boxes[$newPosition] = $currentBox;

					$this->boxes[$i] = 'empty';
				}
			}
		}

		return $this;
	}

	/**
	 * Vide le tableau des alignées.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\AlignableInterface
	 */
	public function emptyAligned(){
		$this->aligned = array();

		return $this;
	}

	/**
	 * Retourne un tableau de cases converti en tableau.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return array_map(function($value)
		{
			return $value instanceof ArrayableInterface ? $value->toArray() : $value;

		}, $this->boxes);
	}

	/**
	 * Simple calculateur de score.
	 *
	 * @return Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
	 */
	public function calculateScore() {

		/* Calc the score */
		$score = 0;
		foreach ($this->aligned as $idBox => $box) {
			$score += $box->getPoints();
		}

		$this->score += $score;

		return $this;
	}

}
