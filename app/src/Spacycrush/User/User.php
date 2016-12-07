<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\User;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * Représente un utilisateur.
 *
 * @author Steven Bsz
 */
class User extends Eloquent implements UserInterface, RemindableInterface {

	protected $table = 'users';

	protected $hidden = array('password');

	protected $guarded = array('id', 'password');

	public $timestamps = false;

	public static $rules = array(
		'username'				=> 'required|alpha|min:2',
		'password'				=> 'required|alpha_num|confirmed',
		'password_confirmation'	=> 'required|alpha_num'
	);

	/**
	 * Scope pour ordonner la query par rang
	 *
	 */
	public function scopeByRank($query)
	{
		return $query->orderBy('rank', 'ASC');
	}

	/**
	 * Scope pour définir le WHERE de la query, en excluant un id
	 *
	 */
	public function scopeWithoutId($query, $id)
    {
        return $query->where('id', '!=', $id);
    }

	/**
	 * Retourne l'id de l'utilisateur.
	 *
	 * @return integer
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Retourne le mot de passe de l'utilisateur.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Retourne l'email de l'utilisateur (inutile dans l'application)
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}


	/**
	 * Retourne tous les utilisateurs triés par score.
	 * Save des utilisateurs si besoin, eg pour un nouveau rang, nouveau meilleur score.
	 *
	 * @param $currentUserScore Le score de l'utilisateur actuel
	 * @return array
	 */
	public function getRankingAllUsers($currentUserScore)
	{
		$savedBestScore = $this->bestScore; // Save meilleur score pour plus tard
		$this->bestScore = $currentUserScore; // Set le score obtenu pour avoir le classement de la partie

		// Get tous les users, sauf l'actuel
		$users = $this->withoutId($this->id)->byRank()->get();

		// Ajoute l'user actuel dans la collection contenant tous les users.
		$users->push($this);

		// Tri la collection d'users par score
		$users = $users->sortByDesc(function($user)
		{
			return $user->bestScore;
		});

		// Reset le rang des users
		$countRank = 1;
		foreach ($users as $key => $user) {
			$user->rank = $countRank;
			$countRank++;
		}

		// Reset les clé du tableau
		$users->values();

		$content = $users->toArray();

		// Change les users dans la db seulement si l'actuel a un nouveau meilleur score
		if( $currentUserScore > $savedBestScore ){
			// Garde seulement les users modifiés
			while( $users->first()->id != $this->id ) {
				$users->shift();
			}

			// Save users
			foreach ($users as $key => $user) {
				$user->save();
			}
		}

		// Get titre pour Angular
		$title = '';
		if( $savedBestScore < $currentUserScore)
			$title = 'New personal record !';
		else
			$title = 'Your personal record : '. $savedBestScore;

		return array('content' => $content, 'title' => $title);
	}

}
