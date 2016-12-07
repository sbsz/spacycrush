<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Controller;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Spacycrush\Cache\CacheFile;
use Spacycrush\Exception\CacheFileNotFoundException;
use Spacycrush\Exception\NoSwitchPossibleException;
use Spacycrush\Exception\SwitchUselessException;
use Spacycrush\Generator\GeneratorInterface;
use Spacycrush\User\User;

/**
 * Contrôleur principal du jeu, c'est ici que tout est controller pour l'execution du jeu.
 *
 * @author Steven Bsz
 */
class GameController extends BaseController
{
	/**
	 * Instance pour le cache fichier
	 *
	 * @var Spacycrush\Cache\CacheFile
	 */
	protected $cacheFile;

	/**
	 * L'utilisateur en cours
	 *
	 * @var Spacycrush\User\USer
	 */
	protected $user;

	/**
	 * Le génerateur de grille
	 *
	 * @var Spacycrush\Generator\GeneratorInterface
	 */
	protected $gridGenerator;

	/* Constructor with dependency injection */
	public function __construct(CacheFile $cacheFile, GeneratorInterface $gridGenerator)
	{
		$this->cacheFile = $cacheFile;
		$this->user = Auth::user();
		$this->gridGenerator = $gridGenerator;
	}

	/**
	 * Action principal qui affiche le jeu.
	 *
	 * @return \Illuminate\View\View
	 */
	public function play()
	{
		return View::make('game.index');
	}

	/* ############### */
	/* ##### API ##### */
	/* ############### */

	/**
	* Initialise le jeu en créant une nouvelle grille et retourne celle-ci au format JSON.
	*
	* @return json
	*/
	public function init()
	{
		// On commence avec un état propre, on supprime les anciennes grille du joueur d'une session précédente.
		$this->cacheFile->delete($this->user->id);

		// Génère la grille
		$grid = $this->gridGenerator->generates();

		// Vérifie d'éventuels alignements
		$align = $grid->checkAlignement();

		// Stocke la grille dans le cache, identifier par l'id de l'utilisateur
		$this->cacheFile->set($this->user->id, $grid);

		//sleep(1); // Fake loading time

		return Response::json([
			'grid' 		=> $grid->toArray(),
			'to_remove' => $align
		]);
	}


	/**
	 * Procède à l'explosion des cases, fait tomber celles qui n'ont plus de voisins en bas, et en régénère de nouvelles cases.
	 * La nouvelle grille modifiée ainsi que les cases alignées, s'il y en a, sont renvoyées au format JSON.
	 *
	 * @return json
	 */
	public function afterCrush()
	{
		try {

			// Récupère la grille mise en cache.
			$grid = $this->cacheFile->get($this->user->id);

			// Génère les nouvelles cases.
			$newBoxes = $this->gridGenerator->generateNewBoxes(count($grid->getAlignedBoxes()));

			// Appelle les méthodes nécessaires lors d'un alignement.
			$grid->doStuffOnAlignedBoxes($newBoxes);

		} catch (CacheFileNotFoundException $e1) {
			return Response::json([
				'message'	=> $e1->getMessage(),
				'error' 	=> $e1->getCode()
			], 400); // Bad request

		}

		// Peut être des alignements ?
		$align = $grid->checkAlignement();

		// Mise en cache de la grille
		$this->cacheFile->set($this->user->id, $grid);

		return Response::json([
			'grid' 		=> $grid->toArray(),
			'to_remove' => $align,
			'to_add' 	=> \Spacycrush\Utility\ArrayMake::make($newBoxes) // Useless !
		]);

	}


	/**
	* Permets l'échange si possible de deux cases.
	*
	* @return json
	*/
	/* TODO - Return only modified boxes, not all */
	public function switchBoxes()
	{
		// Récupère les ids des cases sélectionnées par le joueur.
		$box1 = Input::get('box1');
		$box2 = Input::get('box2');

		try {

			$grid = $this->cacheFile->get($this->user->id);

			// Verifie si l'échange est réalisable, si oui on fait l'échange.
			// Sinon une exception peut être jetée : NoSwitchPossibleException, SwitchUselessException
			$grid->getContainer()->check($box1, $box2)->doSwitch($box1, $box2);

		// Pas de grille trouvé
		} catch (CacheFileNotFoundException $e1) {
			return Response::json([
				'message'	=> $e1->getMessage(),
				'error' 	=> $e1->getCode()
			], 400); // -> Considered as a big mistake, Bad request

		// On ne peut pas échanger les deux cases.
		} catch (NoSwitchPossibleException $e2) {
			return Response::json([
				'error'		=> $e2->getCode()
			]);

		// L'échange ne créer pas d'alignement.
		} catch (SwitchUselessException $e3) {
			return Response::json([
				'error'		=> $e3->getCode()
			]);
		}

		$align = $grid->checkAlignement();

		$this->cacheFile->set($this->user->id, $grid);

		return Response::json([
			'grid' => $grid->toArray(),
			'to_remove' => $align
		]);
	}


	/**
	 * Appelé à la fin du jeu.
	 * Charge le classement avec les scores des autres joueurs, et le score obtenu par notre joueur.
	 *
	 * @return json
	 */
	public function loadRanking() {

		try {

			$grid = $this->cacheFile->get($this->user->id);

		} catch (CacheFileNotFoundException $e1) {
			return Response::json([
				'message'	=> $e1->getMessage(),
				'error' 	=> $e1->getCode()
			], 400); // -> Considered as a big mistake, Bad request

		}

		// Génère le classement.
		$rankUsers = $this->user->getRankingAllUsers($grid->getContainer()->getScore());

		return Response::json(array(
			'content' => View::make('game.ranking', array(
				'users' 		=> $rankUsers['content'],
				'currentUserId' => $this->user->id,
			))->render(),
			'title' => $rankUsers['title']
		));

	}
}
