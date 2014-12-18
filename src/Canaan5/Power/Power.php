<?php
namespace Canaan5\Power;

class Power
{
	public function __construct(\Illuminate\Foundation\Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Get the current Logged in user or nothing if no user is Authenticated
	 */

	public function user()
	{
		return $this->app->auth->user();
	}

	/**
	 * Check if the current logged in or a giving user is super_admin
	 */
	public function oga4Top($userId = null)
	{
		if ( ! is_null($userId))
		{
			if ( $this->group($userId) == 'Oga4Top')
			{
				return true;
			}

			return false;
		}

		if ( $this->group($this->user()->id) == 'Oga4Top')
		{
			return true;
		}

		return false;
	}

	public function group($userId)
	{
		$user = \User::find($userId);

		if ( $user )
		{
			foreach ( $user->groups as $g)
			{
				return $g->name;
			}

		} else {

			return 'User not found';
		}

		return false;

	}


}