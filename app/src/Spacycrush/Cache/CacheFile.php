<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Cache;

use Illuminate\Support\Facades\Cache;

use Spacycrush\Exceptions\CacheFileNotFoundException;

/**
 * Permet de manipuler le cache fichier.
 *
 * @author Steven Bsz
 */
class CacheFile
{

	/**
	 * Récupère un élément en cache en fonction de la clé.
	 *
	 * @param $key La clé pour retrouver l'élément.
	 *
	 * @return mixed|Spacycrush\Exceptions\CacheFileNotFoundException
	 */
	public function get($key)
	{
		$fromCache = Cache::get($key);

		if( is_null($fromCache) ) {
			throw new CacheFileNotFoundException("Cache file with key " . $key . " does not exist");
		}

		return $fromCache;
	}

	/**
	 * Stocke un élément en cache en fonction d'un clé
	 *
	 * @param $key La clé - $value Ce qui est à stocker.
	 *
	 * @return void
	 */
	public function set($key, $value)
	{
		Cache::forever($key, $value);
	}

	/**
	 * Supprime un élément du cache en fonction d'un clé
	 *
	 * @param $key La clé.
	 *
	 * @return void
	 */
	public function delete($key)
	{
		Cache::forget($key);
	}

}
