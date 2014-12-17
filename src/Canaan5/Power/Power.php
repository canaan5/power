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

    public function oga4Top($userId)
    {
        $user = \Group::find($userId);
        echo $user->groups()->pluck('name');
    }
}