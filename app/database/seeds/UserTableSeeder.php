<?php

use Spacycrush\User\User;

class UserTableSeeder extends Seeder {

	public function run() {
		DB::table('users')->delete();

		User::create(
			array(
				'username' => 'game',
				'password' => Hash::make('game')
			)
		);

		$faker = Faker\Factory::create();

		for ($i=0; $i < 15; $i++) {
			User::create(
				array(
					'username' => $faker->userName,
					'password' => Hash::make('password'),
					'bestScore' => $faker->randomNumber(0, 15000), //randomNumber(5)
					'last_login' => new \DateTime
				)
			);
		}

		$users = User::orderBy('bestScore', 'DESC')->get();

		foreach ($users as $key => $user) {
			$user->rank = $key + 1;
			$user->save();
		}
	}
}